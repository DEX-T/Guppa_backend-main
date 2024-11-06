<?php

namespace App\Services\Reports;


use App\Models\User;
use App\Models\MyJob;
use App\Models\GuppaJob;
use App\Models\AppliedJob;
use App\enums\HttpStatusCode;
use App\Models\BidTransaction;
use App\Models\GuppaTransaction;
use App\Domain\DTOs\ApiResponseDto;
use App\Domain\Entities\UserEntity;
use Illuminate\Support\Facades\Log;
use App\Domain\Entities\ContractEntity;
use App\Domain\Entities\GuppaJobEntity;
use App\Domain\Entities\AppliedJobEntity;
use App\Domain\Entities\TransactionEntity;
use App\Domain\Interfaces\Reports\IReportService;
use App\Domain\DTOs\Response\Users\UserResponseDto;
use App\Domain\DTOs\Request\Reports\ReportRequestDto;
use App\Domain\DTOs\Response\Jobs\GuppaJobResponseDto;
use App\Domain\DTOs\Response\Reports\JobReportResponseDto;
use App\Domain\DTOs\Response\Reports\UsersReportResponseDto;
use App\Domain\DTOs\Response\Reports\ContractsReportResponseDto;
use App\Domain\DTOs\Response\Reports\AppliedJobReportResponseDto;
use App\Domain\DTOs\Response\Transactions\TransactionResponseDto;
use App\Domain\DTOs\Response\Reports\TransactionsReportResponseDto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportService implements IReportService
{
    // Implement your service methods here
    public function getJobsReport(Request $request): ApiResponseDto
   {
    try {
        $filters = $request->filters ?? [];
        $query = GuppaJob::orderBy('created_at', 'desc');
        if($filters){
        if ($filters['startDate'] != null && $filters['endDate'] != null) {
            Log::info("parameter date", [$filters['startDate']]);
            $query->whereDate('created_at', '>=', $filters['startDate'])
                  ->whereDate('created_at', '<=', $filters['endDate']);
        }
        
        if ($filters['job_status'] != null) {
            Log::info("parameter job status", [$filters['job_status']]);
            $query->where('job_status', $filters['job_status']);
        }

        if ($filters['job_visibility'] != null) {
            Log::info("parameter job visibility", [$filters['job_visibility']]);
            $query->where('job_visibility', $filters['job_visibility']);
        }

        if ($filters['status'] != null) {
            Log::info("parameter status", [$filters['status']]);
            $query->where('visibility', $filters['status']);
        }
      }

        $data = $filters != null ? $query->get() : $query->limit(20)->get();

        if ($data->isNotEmpty()) {
            $responseDto = $data->map(function ($job) {
                $reportEntity = new GuppaJobEntity($job);
                return new JobReportResponseDto($reportEntity);
            });
            return new ApiResponseDto(true, "Job report", HttpStatusCode::OK, $responseDto->toArray());
        } else {
            return new ApiResponseDto(true, "Not Found", HttpStatusCode::NO_CONTENT);
        }
      
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function getAppliedJobsReport(Request $request): ApiResponseDto
    {
    try {
        $filters = $request->filters ?? [];
        $query = AppliedJob::orderBy('created_at', 'desc');
        
        if($filters){
        if ($filters['startDate'] != null && $filters['endDate'] != null) {
            $query->whereDate('created_at', '>=', $filters['startDate'])
                  ->whereDate('created_at', '<=', $filters['endDate']);
        }

        if ($filters['applied_status'] != null) {
            $query->where('status', $filters['applied_status']);
        }

        if ($filters['payment_type'] != null) {
            $query->where('payment_type', $filters['payment_type']);
        }
    }
    $data = $filters != null ? $query->get() : $query->limit(20)->get();


        if ($data->isNotEmpty()) {
            $responseDto = $data->map(function ($job) {
                $reportEntity = new AppliedJobEntity($job);
                return new AppliedJobReportResponseDto($reportEntity);
            });
            return new ApiResponseDto(true, "Applied jobs report", HttpStatusCode::OK, $responseDto->toArray());
        } else {
            return new ApiResponseDto(true, "Not Found", HttpStatusCode::NO_CONTENT);
        }
    } catch (\Exception $e) {
        return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
    }
    }


   

    public function getTransactionReport(Request $request): ApiResponseDto
    {
        try {
            $filters = $request->filters ?? [];
            // Start query for GuppaTransactions
            $guppaQuery = GuppaTransaction::orderBy('created_at', 'desc');
    
            // Start query for BidTransactions
            $bidQuery = BidTransaction::orderBy('created_at', 'desc');
            if($filters){
                // Apply date range filters if provided
                if ($filters['startDate'] != null && $filters['endDate'] != null) {
                    $guppaQuery->whereDate('created_at', '>=', $filters['startDate'])
                            ->whereDate('created_at', '<=', $filters['endDate']);
                    $bidQuery->whereDate('created_at', '>=', $filters['startDate'])
                            ->whereDate('created_at', '<=', $filters['endDate']);
                }
                if ($filters['transaction_status'] != null) {
                    $guppaQuery->where('status', $filters['transaction_status']);
                    $bidQuery->where('status', $filters['transaction_status']);
                }
        
            }
            // Get results from both tables
            $guppaTransactions = $guppaQuery->get();
            $bidTransactions = $bidQuery->get();
           
            $guppaTransactions = collect($guppaTransactions);
            $bidTransactions = collect($bidTransactions);
            // Merge results and map to response DTO
            $allTransactions = $guppaTransactions->map(function ($transaction) {
                $transaction->type = 'job payment'; 
                return $transaction;
            })->concat(
                $bidTransactions->map(function ($transaction) {
                    $transaction->type = 'bid'; 
                    return $transaction;
                })
            );
            // dd($allTransactions);
            if ($allTransactions->isNotEmpty()) {
                $responseDto = $allTransactions->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'amount' => $transaction->amount,
                        'reference' => $transaction->reference ?? $transaction->tnx_ref, 
                        'order_id' => $transaction->orderId ?? $transaction->order_id,
                        'status' => $transaction->status,
                        'created_at' => $transaction->created_at,
                        'updated_at' => $transaction->updated_at,
                        'type' => $transaction->type
                    ];
                });   
    
                return new ApiResponseDto(true, "Transaction report", HttpStatusCode::OK, $responseDto->toArray());
            } else {
                return new ApiResponseDto(false, "No transactions found", HttpStatusCode::NOT_FOUND);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error: " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
    

   
   
   public function getUsersReport(Request $request): ApiResponseDto
   {
       try {
           $filters = $request->filters ?? [];
           $query = User::orderBy('created_at', 'desc');
           if($filters){
           if ($filters['startDate'] != null && $filters['endDate'] != null) {
               $query->whereDate('created_at', '>=', $filters['startDate'])
                     ->whereDate('created_at', '<=', $filters['endDate']);
           }
   
           if ($filters['status'] != null) {
               $query->where('status', $filters['status']);
           }
   
           if ($filters['role'] != null) {
               $query->where('role', strtoupper($filters['role']));
           }
   
           if ($filters['country'] != null) {
               $query->where('country', strtoupper($filters['country']));
           }
        }
        $data = $filters != null ? $query->get() : $query->limit(20)->get();

   
           if ($data->isNotEmpty()) {
               $responseDto = $data->map(function ($user) {
                   $reportEntity = new UserEntity($user);
                   return new UsersReportResponseDto($reportEntity);
               });
               return new ApiResponseDto(true, "Users report", HttpStatusCode::OK, $responseDto->toArray());
           } else {
               return new ApiResponseDto(true, "Not Found", HttpStatusCode::NO_CONTENT);
           }
       } catch (\Exception $e) {
           return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
       }
   }

   public function getContractsReport(Request $request): ApiResponseDto
   {
        try {
            $filters = $request->filters ?? [];
            $query = MyJob::orderBy('created_at', 'desc');
            if($filters){
            if ($filters['startDate'] != null && $filters['endDate'] != null) {
                $query->whereDate('created_at', '>=', $filters['startDate'])
                    ->whereDate('created_at', '<=', $filters['endDate']);
            }

            if ($filters['contract_status'] != null) {
                $query->where('status', $filters['contract_status']);
            }
        }
        $data = $filters != null ? $query->get() : $query->limit(20)->get();


            if ($data->isNotEmpty()) {
                $responseDto = $data->map(function ($job) {
                    $reportEntity = new ContractEntity($job);
                    return new ContractsReportResponseDto($reportEntity);
                });
                return new ApiResponseDto(true, "Contracts report", HttpStatusCode::OK, $responseDto->toArray());
            } else {
                return new ApiResponseDto(true, "Not Found", HttpStatusCode::NO_CONTENT);
            }
        } catch (\Exception $e) {
            return new ApiResponseDto(false, "Server error " . $e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

   
}
