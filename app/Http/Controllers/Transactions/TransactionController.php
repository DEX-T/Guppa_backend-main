<?php

namespace App\Http\Controllers\Transactions;

use Carbon\Carbon;
use App\Models\Bid;
use App\Models\MyJob;
use App\Models\Order;
use App\Models\GuppaJob;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Helpers\GeneralHelper;
use App\Models\BidTransaction;
use App\Models\GuppaTransaction;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Unicodeveloper\Paystack\Facades\Paystack;
use App\Domain\Interfaces\Job\IGuppaJobService;
use App\Domain\Interfaces\Transactions\ITransactionService;
use App\Models\AppliedJob;
use App\Models\PendingApprovedJobPayment;

class TransactionController extends Controller
{
    private ITransactionService $_transactionService;
    protected $_currentUser;

    function __construct(ITransactionService $transactionService)
    {
        $this->_transactionService = $transactionService;
        $this->_currentUser = Auth::user();
    }

    /**
     * @OA\Get(
     *     path="/api/transaction/get_all_transactions",
     *     operationId="getAllTranctions",
     *     tags={"Transaction"},
     *     summary="Get list of all transactions",
     *     description="Returns list of all transactions",
     *     security={{"sanctum":{}}},
     *      @OA\Response(
     *         response=200,
     *         description="successful operation"
     *
     *     ),
     * )
     */

    //Get All roles
    public function getAllPayments(): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_transactionService->getAllPayments();
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ]);

    }

    /**
     * @OA\Get(
     *     path="/api/transaction/get_payment_by_id/{id}",
     *     operationId="getPaymentById",
     *     tags={"Transaction"},
     *     summary="Get payment by Id",
     *     description="Returns payment details",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */

    public function getPaymentById(Request $request): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_transactionService->getPaymentById($request->id);
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ]);

    }

    /**
     * @OA\Get(
     *     path="/api/transaction/get_client_payments",
     *     operationId="getClientPayments",
     *     tags={"Transaction"},
     *     summary="Get  list of client payments",
     *     description="Returns list of client payments",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */

    public function getAllClientPayments(Request $request): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_transactionService->getAllClientPayments();
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ]);

    }

    /**
     * @OA\Get(
     *     path="/api/transaction/get_freelancer_payments",
     *     operationId="getFreelancerPayments",
     *     tags={"Transaction"},
     *     summary="Get  list of freelancer payments",
     *     description="Returns list of freelancer payments",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */

    public function getAllFreelancerPayments(Request $request): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_transactionService->getAllFreelancerPayments();
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ]);

    }

    /**
     * @OA\Get(
     *     path="/api/transaction/get_total_income_payouts/{user_id}",
     *     operationId="getFreelancerIncomePayouts",
     *     tags={"Transaction"},
     *     summary="Get  list of freelancer total income and payouts",
     *     description="Returns list of freelancer total income and payouts",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */

    public function getEarning(Request $request): \Illuminate\Http\JsonResponse
    {
        $roleDto = $this->_transactionService->getEarnings($request->user_id);
        return response()->json([
            'success' => $roleDto->status,
            'message' => $roleDto->message,
            'data' => $roleDto->data
        ]);

    }


    
    /**
     * @OA\Post(
     *     path="/api/transaction/pay",
     *     operationId="makePayment",
     *     tags={"Transaction"},
     *     security={{"sanctum":{}}},
     *     summary="Buy bid",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"bid"},
     *             @OA\Property(property="bid", type="int", example="10")
     *         )
     *     ),
     *   @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     * )
     */
    public function redirectToGateway(Request $request)
    {
       
        try{
            $data = $this->_transactionService->buyBid($request);
            Log::info("data on controller ", [$data]);
            $url = Paystack::getAuthorizationUrl($data->data);
            return response()->json([
                'success' => true,
                'message' => 'url',
                'url' => $url
            ]);
        }catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } 
    }

     /**
     * @OA\Get(
     *     path="/api/transaction/payment/callback?trxref={ref1}&reference={ref2}",
     *     operationId="PaymentCallBack",
     *     tags={"Transaction"},
     *     security={{"sanctum":{}}},
     *     summary="Payment callback",
     *     description="Returns payment callback",
     *   @OA\Parameter(
     *         name="trxref",
     *         in="path"
     *     ),
     *   @OA\Parameter(
     *         name="reference",
     *         in="path"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     * )
     */
    public function handleGatewayCallback(Request $request)
    {
        // $status = $this->_transactionService->getWayCallback($request);
        // return response()->json([
        //     'success' => $status->status,
        //     'message' => $status->message,
        //     'data' => $status->data
        // ], $status->code);

        $data = Paystack::getPaymentData();
        if($data['status']):
            //update transaction table if successful
            $trans = BidTransaction::where('reference', $data['data']['reference'])->first();
            $trans->status = 'completed';
            $trans->save();

            //get order
            $order = Order::where('order_number', $trans->orderId)->first();
            $order->order_status = 'completed';
           $order->save();

           $bid = Bid::where('user_id',  $this->_currentUser->id)->first();
           if($bid == null){
                $bid = new Bid();
                $bid->user_id = $this->_currentUser->id;
                $bid->bid = $order->quantity;
                $bid->status = "active";
                $bid->save();
             }else{
                $bid->bid = $bid->bid + $order->quantity;
                $bid->save();
             }
            //return status  if successful
            $msg = "You have successfully Made payment for your bid!";
            $dto = [  
                'orderId' => $trans->orderId,
                'date' => $data['data']['created_at'],
                'bid_points' => $bid->bid,
                'reference' => $data['data']['reference']
            ];
            // return redirect()->away(env('FRONTEND_LOCAL_URL').'dashboard/payment/success?reference='.$data['data']['reference']);
            return response()->json([
                    'success' => true,
                    'message' => $msg,
                    'data' => $dto
                ], 200);
            
        elseif($data === 'cancelled'):
            return response()->json([
                'success' => true,
                'message' => "payment canceled",
            ], 400);
        else:
            return response()->json([
                'success' => true,
                'message' => "payment failed",
            ], 401);
        endif;
    }



     
    /**
     * @OA\Post(
     *     path="/api/transaction/payment/verify_payment/{reference}",
     *     operationId="verifyPayment",
     *     tags={"Transaction"},
     *     security={{"sanctum":{}}},
     *     summary="verify payment",
     *   @OA\Parameter(
     *         name="reference",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *   @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     * )
     */
    public function verifyPayment(Request $request)
    {
       
        $data = $this->_transactionService->verifyPayment($request);
        Log::info("data on controller ", [$data]);
            if($data->status){
                //update transaction table if successful
                $trans = BidTransaction::where('reference', $request->reference)->first();
                if($trans != null){
                    $trans->forceFill([
                        'status' => "completed",
                        "updated_at" => Carbon::now()
                    ])->save();
                    Log::info("transaction data on controller ", [$trans]);

                //get order
                $order = Order::where('order_number', $trans->orderId)->first();
                Log::info("order data on controller ", [$order]);

                $order->order_status = 'completed';
                $order->save();
               
                Log::info("user ", [$this->_currentUser]);
               $bid = Bid::where('user_id',  $this->_currentUser->id)->first();
               Log::info("bid ", [$bid]);

               if($bid == null){
                    $bid = new Bid();
                    $bid->user_id = $this->_currentUser->id;
                    $bid->bid = $order->quantity;
                    $bid->active = true;
                    $bid->created_at = Carbon::now();
                    $bid->save();
                 }else{
                    $bid->bid = $bid->bid + $order->quantity;
                    $bid->updated_at = Carbon::now();
                    $bid->save();
                 }
                //return status  if successful
                $msg = "You have successfully Made payment for your bid!";
                $dto = [  
                    'orderId' => $trans->orderId,
                    'bid_points' => $bid->bid,
                    'reference' => $request->reference
                ];
                $notification = new Notification();
                $notification->user_id = $this->_currentUser->id;
                $notification->title = "Bid purchase";
                $notification->message = "Bid purchase completed";
                $notification->created_at = Carbon::now();
                $notification->isRead = false;
                $notification->save();
                return response()->json([
                        'success' => true,
                        'message' => $msg,
                        'data' => $dto
                    ], 200);
             }
            }elseif($data === 'cancelled'){
                return response()->json([
                    'success' => true,
                    'message' => "payment canceled",
                ], 400);
            }else{
                return response()->json([
                    'success' => false,
                    'message' =>  "Payment could not be verified please reach out to support",
                ], 401);
            }
       
    
    }


     /**
     * @OA\Post(
     *     path="/api/transaction/pay-guppa",
     *     operationId="makePaymentToGuppa",
     *     tags={"Transaction"},
     *     security={{"sanctum":{}}},
     *     summary="Make Payment for approved job",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"payment_id"},
     *             @OA\Property(property="payment_id", type="int", example=10)
     *         )
     *     ),
     *   @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     * )
     */
    public function payGuppa(Request $request)
    {
       
        try{
            $data = $this->_transactionService->payForApprovedJob($request);
            Log::info("data on controller ", [$data]);
            $url = Paystack::getAuthorizationUrl($data->data);
            return response()->json([
                'success' => true,
                'message' => 'url',
                'url' => $url
            ]);
        }catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        } 
    }
  
    /**
     * @OA\Post(
     *     path="/api/transaction/payment/verify_guppa_payment/{reference}",
     *     operationId="verifyGuppaPayment",
     *     tags={"Transaction"},
     *     security={{"sanctum":{}}},
     *     summary="verify guppa payment",
     *   @OA\Parameter(
     *         name="reference",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *   @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     * )
     */
    public function verifyGuppaPayment(Request $request)
    {
       
        $data = $this->_transactionService->verifyGuppaPayment($request);
        Log::info("data on controller ", [$data]);
            if($data->status){
                Log::info("status is successful verification started");
                //update transaction table if successful
                $trans = GuppaTransaction::where('tnx_ref', $request->reference)->first();
                if($trans != null){
                    $trans->forceFill([
                        'status' => "completed",
                        "updated_at" => Carbon::now()
                    ])->save();
                    Log::info("updated transactions ", [$trans]);
                //get order
                $order = Order::where('id', $trans->order_id)->first();
                $order->order_status = 'completed';
                $order->save();
                Log::info("updated order ", [$order]);
               //approve job
                $this->updateJobAfterPayment($trans->guppa_job_id);
                //return status  if successful
                $msg = "You have successfully Made payment for this job, freelancer will be notified to start work!";
                $dto = [  
                    'orderId' => $trans->orderId,
                    'reference' => $request->reference
                ];
                return response()->json([
                        'success' => true,
                        'message' => $msg,
                        'data' => $dto
                    ], 200);
             }
            }elseif($data === 'cancelled'){
                return response()->json([
                    'success' => true,
                    'message' => "payment canceled",
                ], 400);
            }else{
                return response()->json([
                    'success' => false,
                    'message' =>  "Payment could not be verified please reach out to support",
                ], 401);
            }
       
    
    }
    //applied id is not corrected store so include it on order number then split to get applied id
    private function updateJobAfterPayment($jobId){
        $applied = AppliedJob::where('guppa_job_id', $jobId)->where('status', 'approved')->first();
        Log::info("applied job", [$applied]);

        $payment = PendingApprovedJobPayment::where('applied_id', $applied->id)->where('status', 'pending')->first();
        Log::info("payment", [$payment]);

        $payment->status = "completed";
        $payment->date_paid = Carbon::now();
        if($payment->save()){
            
        $userJob =  new MyJob();
        $userJob->user_id = $applied->user_id;
        $userJob->guppa_job_id = $applied->guppa_job_id;
        $userJob->client_id = $this->_currentUser->id;
        $userJob->applied_job_id = $applied->id;
        $userJob->status = "In Progress";
        $userJob->save();

      

        $notification = new Notification();
        $notification->user_id = $applied->user_id;
        $notification->title = "Job Approved";
        $notification->message = "Your Job application ". $applied->title ." have been approved, you can start working on it";
        $notification->created_at = Carbon::now();
        $notification->isRead = false;
        $notification->save();
        return true;
        }
    }


    
     /**
     * @OA\Get(
     *     path="/api/transaction/payment/pending-job-payments",
     *     operationId="PaymentJobPayments",
     *     tags={"Transaction"},
     *     security={{"sanctum":{}}},
     *     summary="get all client approved jobs that payment has not been made",
     *     description="Returns all client approved jobs that payment has not been made",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     * )
     */
    public function pendingJobPayments()
    {
        
        $data = $this->_transactionService->pendingPayment();
        return response()->json([
                'success' => $data->status,
                'message' => $data->message,
                'data' => $data->data
            ], $data->code);
            
      
    }
}
