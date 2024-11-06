<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\Guppa;
use App\Models\MyJob;
use App\Models\GuppaJob;
use App\Models\AppliedJob;
use App\Models\Verification;
use App\enums\HttpStatusCode;
use App\Models\SupportTicket;
use App\Models\BidTransaction;
use Illuminate\Support\Carbon;
use App\Models\GuppaTransaction;
use App\Domain\DTOs\ApiResponseDto;
use App\Domain\Entities\UserEntity;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Domain\Interfaces\Dashboard\IDashboardService;
use App\Helpers\GeneralHelper;
use App\Models\Notification;

class DashboardService implements IDashboardService
{
    protected $_currentUser;
    protected $_myJobs;
    protected $_contractJobs;
    protected $_hourlyJobs;
    protected $_inProgressJobs;
    protected $_completedJobs;
    protected $_inReviewJobs;
    protected $_jobs;

    public function __construct() {
        $this->_currentUser = Auth::user();
    }
    public function GetClientTables(){
       try {
        $jobs = GuppaJob::where('user_id', $this->_currentUser->id)->active()->orderBy('created_at', 'desc')->limit(6);
        $contracts = MyJob::where(['client_id'=> $this->_currentUser->id])->orderBy('created_at', 'desc')->limit(6);
        Log::info("contracts ", [$contracts->get()]);
        $jobPosted = $jobs->available()->get();
        $jobContract = $jobs->available()->contract()->get();
        $jobHourly =$jobs->available()->hourly()->get();
       
        $jobInProgress = MyJob::where(['client_id'=> $this->_currentUser->id])->progress()->orderBy('created_at', 'desc')->limit(6)->get();
        $jobInReview = MyJob::where(['client_id'=> $this->_currentUser->id])->review()->orderBy('created_at', 'desc')->limit(6)->get();
        $jobDone =MyJob::where(['client_id'=> $this->_currentUser->id])->done()->orderBy('created_at', 'desc')->limit(6)->get();

        if($jobPosted->isNotEmpty()){
            $this->_myJobs = $jobPosted->map(function($job){
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'slug' => $job->slug,
                    'amount' => $job->amount,
                    'status' => $job->job_status,
                    'created_at' => $job->created_at
                ];
            })->toArray();
        }

        if($jobContract->isNotEmpty()){
            $this->_contractJobs = $jobContract->map(function($job){
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'slug' => $job->slug,
                    'amount' => $job->amount,
                    'status' => $job->job_status,
                    'created_at' => $job->created_at
                ];
            })->toArray();
        }
        if($jobHourly->isNotEmpty()){
            $this->_hourlyJobs = $jobHourly->map(function($job){
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'slug' => $job->slug,
                    'amount' => $job->amount,
                    'status' => $job->job_status,
                    'created_at' => $job->created_at
                ];
            })->toArray();
        }
        if($jobInProgress->isNotEmpty()){
            $this->_inProgressJobs = $jobInProgress->map(function($job){
                return [
                    'id' => $job->id,
                    'freelancer' => $this->getFreelancer($job->user_id),
                    'progress' => $job->progress,
                    'status' => $job->status,
                    'created_at' => $job->created_at
                ];
            })->toArray();
        }
        if($jobInReview->isNotEmpty()){
            $this->_inReviewJobs = $jobInReview->map(function($job){
                return [
                    'id' => $job->id,
                    'freelancer' => $this->getFreelancer($job->user_id),
                    'progress' => $job->progress,
                    'status' => $job->status,
                    'created_at' => $job->created_at
                ];
            })->toArray();
        }
        if($jobDone->isNotEmpty()){
            Log::info("contracts done", [$jobDone]);

            $this->_completedJobs = $jobDone->map(function($job){
                return [
                    'id' => $job->id,
                    'freelancer' => $this->getFreelancer($job->user_id),
                    'progress' => $job->progress,
                    'status' => $job->status,
                    'created_at' => $job->created_at
                ];
            })->toArray();
            Log::info("completed jobs ", [$this->_completedJobs]);

        }

        $dto = [
            "latest_jobs" => $this->_myJobs ?? [],
            "hourly_jobs" => $this->_hourlyJobs ?? [],
            "in_progress_jobs" => $this->_inProgressJobs ?? [],
            "in_review_jobs" => $this->_inReviewJobs ?? [],
            'contract_jobs' => $this->_contractJobs ?? [],
            'completed_jobs' => $this->_completedJobs ?? []
        ];
         return new ApiResponseDto(true, 'fetched data', HttpStatusCode::OK, $dto);
       } catch (\Exception $e) {
         return new ApiResponseDto(false, 'Server Error '.$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
       }
    }


    public function ClientStatistics(){
        // Group jobs by month and get totals
        $jobs = GuppaJob::where('user_id', $this->_currentUser->id)
            ->selectRaw("
                DATE_FORMAT(created_at, '%Y-%M') as month,
                COUNT(*) as total_posted,
                SUM(job_status = 'taken') as total_approved
            ")
            ->active()
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        // Group contracts by month and get totals for different statuses
        $contracts = MyJob::where('client_id', $this->_currentUser->id)
            ->selectRaw("
                DATE_FORMAT(created_at, '%Y-%M') as month,
                SUM(status = 'In Progress') as in_progress,
                SUM(status = 'Awaiting Review') as in_review,
                SUM(status = 'Done') as completed
            ")
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        // Merge and format the results for the frontend
        $dashboardData = $this->mergeDashboardData($jobs, $contracts);
        return new ApiResponseDto(true, "data", HttpStatusCode::OK, $dashboardData);
       
    }

    public function GetAdminStatistics(){
        // Group jobs by month and get totals
        $jobs = GuppaJob::query()
            ->selectRaw("
                DATE_FORMAT(created_at, '%Y-%M') as month,
                COUNT(*) as total_posted,
                SUM(job_status = 'taken') as total_approved,
                SUM(job_status = 'available') as total_available
            ")
            ->active()
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        // Group contracts by month and get totals for different statuses
        $contracts = MyJob::query()
            ->selectRaw("
                DATE_FORMAT(created_at, '%Y-%M') as month,
                SUM(status = 'In Progress') as in_progress,
                SUM(status = 'Awaiting Review') as in_review,
                SUM(status = 'Done') as completed
            ")
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        // Merge and format the results for the frontend
        $dashboardData = $this->mergeDashboardData($jobs, $contracts);
        return new ApiResponseDto(true, "data", HttpStatusCode::OK, $dashboardData);
       
    }

    public function GetAdminTables(){
        try {
         $jobs = GuppaJob::with('user')->active()->orderBy('created_at', 'desc')->limit(12)->get();
        
         $jobInProgress = MyJob::progress()->orderBy('created_at', 'desc')->limit(12)->get();
         $jobInReview = MyJob::review()->orderBy('created_at', 'desc')->limit(12)->get();
         $jobDone =MyJob::done()->orderBy('created_at', 'desc')->limit(12)->get();
 
       
 
         if($jobs->isNotEmpty()){
             $this->_jobs = $jobs->map(function($job){
                 return [
                     'id' => $job->id,
                     'client' => [
                        'name' => $job->user->first_name . " " .$job->user->last_name,
                        'email' => $job->user->email,
                        'profile_pic' => asset('storage/app/public/uploads/'.$job->user->profile_photo),
                     ],
                     'title' => $job->title,
                     'slug' => $job->slug,
                     'amount' => $job->amount,
                     'status' => $job->job_status,
                     'created_at' => $job->created_at
                 ];
             })->toArray();
         }
        
         if($jobInProgress->isNotEmpty()){
             $this->_inProgressJobs = $jobInProgress->map(function($job){
                 return [
                     'id' => $job->id,
                     'freelancer' => $this->getFreelancer($job->user_id),
                     'client' => $this->getClient($job->client_id),
                     'type' => $this->getJobType($job->guppa_job_id),
                     'progress' => $job->progress,
                     'status' => $job->status,
                     'created_at' => $job->created_at
                 ];
             })->toArray();
         }
         if($jobInReview->isNotEmpty()){
             $this->_inReviewJobs = $jobInReview->map(function($job){
                 return [
                     'id' => $job->id,
                     'freelancer' => $this->getFreelancer($job->user_id),
                     'client' => $this->getClient($job->client_id),
                     'type' => $this->getJobType($job->guppa_job_id),
                     'progress' => $job->progress,
                     'status' => $job->status,
                     'created_at' => $job->created_at
                 ];
             })->toArray();
         }
         if($jobDone->isNotEmpty()){
             Log::info("contracts done", [$jobDone]);
 
             $this->_completedJobs = $jobDone->map(function($job){
                 return [
                     'id' => $job->id,
                     'freelancer' => $this->getFreelancer($job->user_id),
                     'client' => $this->getClient($job->client_id),
                     'type' => $this->getJobType($job->guppa_job_id),
                     'progress' => $job->progress,
                     'status' => $job->status,
                     'created_at' => $job->created_at
                 ];
             })->toArray();
             Log::info("completed jobs ", [$this->_completedJobs]);
 
         }
 
         $dto = [
             "latest_jobs" => $this->_jobs ?? [],
             "in_progress_jobs" => $this->_inProgressJobs ?? [],
             "in_review_jobs" => $this->_inReviewJobs ?? [],
             'completed_jobs' => $this->_completedJobs ?? []
         ];
          return new ApiResponseDto(true, 'fetched data', HttpStatusCode::OK, $dto);
        } catch (\Exception $e) {
          return new ApiResponseDto(false, 'Server Error '.$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
 
 
    public function GetCounters(){
        // total freelancers, total recruiters, active users, Job completion rate, total income, total jobs posted, total contracts,/
        // total verification submitted, total verified client,

         // Count total freelancers
         $totalFreelancers = User::freelancers()->count();

         // Count total recruiters
         $totalRecruiters = User::clients()->count();
 
         // Count active users (assuming there is an 'active' status or similar field)
         $activeUsers = User::where('role', '!=', 'ADMIN')->where('role', '!=', 'SUPERUSER')->active()->count();
 
         // Calculate job completion rate (assuming jobs have a 'status' field)
         $totalJobs = MyJob::count();
         $completedJobs = MyJob::done()->count();
        
         $jobCompletionRate = $totalJobs > 0 ? ($completedJobs / $totalJobs) * 100 : 0;
 
         // Sum total income (assuming contracts have an 'income' field)
         $guppaTxn = GuppaTransaction::where(['status' => 'completed', 'type' => 'income'])->sum('amount');
         $bidTxn = BidTransaction::where(['status' => 'completed'])->sum('amount');

         $totalIncome = $guppaTxn + $bidTxn;
 
         // Count total jobs posted
         $totalJobsPosted = GuppaJob::count();
 
         // Count total contracts
         $totalContracts =  $totalJobs;
 
         // Count total verification submitted (assuming verifications have a 'submitted' status)
         $totalVerificationsSubmitted = Verification::where('status', 'processing')->count();
 
         // Count total verified clients (assuming verifications have a 'verified' status)
         $totalVerifiedClients = Verification::where('status', 'approved')->count();
         // Count total rejected clients (assuming verifications have a 'verified' status)
         $totalRejectedClients = Verification::where('status', 'rejected')->count();
         
         $visitorsToday = User::where('role', '!=', 'SUPERUSER')->where('role', '!=', 'ADMIN')->where('last_login', Carbon::today())->count();
         // Return the counts as a JSON response
         $data = [
             'total_freelancers' => $totalFreelancers,
             'total_recruiters' => $totalRecruiters,
             'active_users' => $activeUsers,
             'job_completion_rate' => $jobCompletionRate,
             'total_income' => $totalIncome,
             'total_jobs_posted' => $totalJobsPosted,
             'total_contracts' => $totalContracts,
             'total_verifications_submitted' => $totalVerificationsSubmitted,
             'total_verified_clients' => $totalVerifiedClients,
             'total_rejected_clients' => $totalRejectedClients,
             'visitors_today' => $visitorsToday
         ];
         return new ApiResponseDto(true, "counters", HttpStatusCode::OK, $data);

    }

   
    public function GetLatestSupportTickets(){
        $messages = SupportTicket::where('status','active')->orderBy('created_at', 'desc')
        ->take(50)
        ->get();

        if($messages->isNotEmpty()){
            $dto = $messages->map(function($message){
                return [
                    'message' => $message->message,
                    'user_name' => $this->getUser($message->user_id)['name'],
                    'date' => Carbon::parse($message->created_at)->format('j, F Y'),
                    'time' => GeneralHelper::timeAgo($message->created_at)
                ];
            });
            return new ApiResponseDto(true, "tickets", HttpStatusCode::OK, $dto->toArray());
            }
            return new ApiResponseDto(true, "Not latest support tickets", HttpStatusCode::OK);
    }


    public function  GetLatestUsers(){
        $users = User::where('role', '!=', 'ADMIN')->where('role', '!=', 'SUPERUSER')->orderBy('created_at', 'desc')
                 ->take(20)
                 ->get();
        if($users->isNotEmpty()){
            $dto = $users->map(function($user){
                return [
                    'name' => $user->first_name . " " . $user->last_name,
                    'role' => $user->role,
                    'profile_pic' => asset('storage/app/public/uploads/'.$user->profile_photo)
                ];
            });
            return new ApiResponseDto(true, "users", HttpStatusCode::OK, $dto->toArray());
        }
        return new ApiResponseDto(true, "Not latest users", HttpStatusCode::OK);
    }
   

    private function mergeDashboardData($jobs, $contracts)
    {
        $data = [];

        // Process Jobs Data
        foreach ($jobs as $job) {
            $month = $job->month;
            if (!isset($data[$month])) {
                $data[$month] = [
                    'month' => $month,
                    'total_posted' => 0,
                    'total_approved' => 0,
                    'in_progress' => 0,
                    'in_review' => 0,
                    'completed' => 0,
                ];
            }
            $data[$month]['total_posted'] = $job->total_posted;
            $data[$month]['total_approved'] = $job->total_approved;
        }

        // Process Contracts Data
        foreach ($contracts as $contract) {
            $month = $contract->month;
            if (!isset($data[$month])) {
                $data[$month] = [
                    'month' => $month,
                    'total_posted' => 0,
                    'total_approved' => 0,
                    'in_progress' => 0,
                    'in_review' => 0,
                    'completed' => 0,
                ];
            }
            $data[$month]['in_progress'] = $contract->in_progress;
            $data[$month]['in_review'] = $contract->in_review;
            $data[$month]['completed'] = $contract->completed;
        }

        // Convert data to array and sort by month
        return array_values($data);
    }

    private function getFreelancer($id){
        $freelancer =  User::where('id',$id)->first();
        return [
            'id' => $freelancer->id,
            'name' => $freelancer->last_name . " " . $freelancer->first_name,
            'email' => $freelancer->email,
            'profile_pic' => asset('storage/app/public/uploads/'.$freelancer->profile_photo),
        ];
        
    }

    private function getUser($id){
        $client =  User::where('id',$id)->first();
        return [
            'name' => $client->last_name . " " . $client->first_name,
        ];
        
    }

    private function getClient($id){
        $client =  User::where('id',$id)->first();
        return [
            'name' => $client->last_name . " " . $client->first_name,
            'email' => $client->email,
            'profile_pic' => asset('storage/app/public/uploads/'.$client->profile_photo),
        ];
        
    }

    private function getJobType($id){
        $job_type =  GuppaJob::where('id',$id)->first()->project_type;
        return $job_type;
    }

}

