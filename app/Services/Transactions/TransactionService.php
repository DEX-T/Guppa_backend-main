<?php
namespace App\Services\Transactions;

use Carbon\Carbon;
use App\Models\Bid;
use App\Models\MyJob;
use App\Models\Order;
use App\enums\UserRoles;
use App\Models\GuppaJob;
use App\Models\AppliedJob;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\enums\HttpStatusCode;
use App\Helpers\GeneralHelper;
use App\Models\BidTransaction;
use App\Helpers\UserRoleHelper;
use App\Models\GuppaTransaction;
use App\Domain\DTOs\ApiResponseDto;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Domain\Entities\PaymentsEntity;
use App\Models\PendingApprovedJobPayment;
use Illuminate\Support\Facades\Validator;
use App\Domain\Entities\TransactionEntity;
use Unicodeveloper\Paystack\Facades\Paystack;
use App\Domain\DTOs\Request\Bid\BidRequestDto;
use App\Domain\Interfaces\Transactions\ITransactionService;
use App\Domain\DTOs\Response\Transactions\EarningResponseDto;
use App\Domain\DTOs\Response\Transactions\PaymentsResponseDto;
use App\Domain\DTOs\Response\Transactions\TransactionResponseDto;
use App\Domain\DTOs\Response\pendingPayment\PendingApprovedJobPaymentResponseDto;

class TransactionService implements ITransactionService
{
    protected $_currentUser;
    protected $transRef;
    protected $orderId;
    protected $amount;
    public function __construct(){
        $this->_currentUser = Auth::user();
    }
    
    // Implement your service methods here
    public function getAllPayments()
    {
        try {
            $payments = GuppaTransaction::all();
            $dto = $payments->map(function ($payment) {
                $paymentEntity = new TransactionEntity($payment);
                return new TransactionResponseDto($paymentEntity);
            });
            return new ApiResponseDto(true, "successful", HttpStatusCode::OK, $dto->toArray());
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server Error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllClientPayments()
    {
        try {
            $paymentsJob = GuppaTransaction::where('user_id', $this->_currentUser->id)->orderBy('created_at', 'desc')->get();
            $paymentsBid = BidTransaction::where('user_id', $this->_currentUser->id)->get();

            if($paymentsJob->isNotEmpty()){
                $dto = $paymentsJob->map(function ($payment) {
                    $paymentEntity = new TransactionEntity($payment);
                    return new TransactionResponseDto($paymentEntity);
                });
            }else{
                return [];
            }
            if($paymentsBid->isNotEmpty()){
                $bid = $paymentsBid->map(function ($payment) {
                    $paymentEntity = new PaymentsEntity($payment);
                    return new PaymentsResponseDto($paymentEntity);
                });
            }else{
                return [];
            }
            $data = [
                'job_payments' => $dto->toArray(),
                'bid_payments' => $bid->toArray()
            ];
            return new ApiResponseDto(true, "successful", HttpStatusCode::OK, $data);
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server Error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllFreelancerPayments()
    {
        try {
            $payments = BidTransaction::where('user_id', $this->_currentUser->id)->get();
            $dto = $payments->map(function ($payment) {
                $paymentEntity = new PaymentsEntity($payment);
                return new PaymentsResponseDto($paymentEntity);
            });
            return new ApiResponseDto(true, "successful", HttpStatusCode::OK, $dto->toArray());
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server Error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getPaymentById(int $Id)
    {

        try {
            $payment = GuppaTransaction::findOrFail($Id);
           if($payment != null){
               $paymentEntity = new TransactionEntity($payment);
               $dto = new TransactionResponseDto($paymentEntity);
            return new ApiResponseDto(true, "successful", HttpStatusCode::OK, $dto->toArray());
           }else{
               return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
           }
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server Error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function getEarnings(int $userId)
    {
        try {
            $payment = GuppaTransaction::where('user_id', $userId)->get();
            if($payment != null){
                $dto = new EarningResponseDto($payment);
                return new ApiResponseDto(true, "successful", HttpStatusCode::OK, $dto->toArray());
           }else{
                return new ApiResponseDto(false, "Not Found", HttpStatusCode::NOT_FOUND);
            }
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server Error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    public function buyBid(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'bid' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation Error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validate = $validator->validated();
       
            $dto = new BidRequestDto($validate['bid']);
            $bid = $dto->bid;
            $amount = $dto->amount;
            Log::info("Amount: " . $amount);

            $existingOrder = Order::where(['user_id' => $this->_currentUser->id, 'order_status' => 'pending'])->first();

            Log::info("Existing Order check: " . $existingOrder);
            $transRef = uniqid().time();
            if($existingOrder != null){
                Log::info("Existing Order Is not null: " . $existingOrder);

                $existingOrder->total_amount = $amount;
                $existingOrder->quantity = $bid;
                $existingOrder->save();
               
                $existingTrans = BidTransaction::where(['orderId' => $existingOrder->order_number, 'status' => 'pending'])->first();
                Log::info("Existing Transaction check: " . $existingTrans);

                if($existingTrans != null){
                    Log::info("Existing Transaction is not null: " . $existingTrans);

                    $existingTrans->amount = $existingOrder->total_amount;
                    $existingTrans->reference = $transRef;
                    $existingTrans->save();
                }

                $this->orderId = $existingOrder->order_number;
                $this->transRef = $transRef;
                $this->amount = $existingOrder->total_amount;
                
                Log::info("Updated data order id: " . $this->orderId);
                Log::info("Updated data transaction reference: " . $this->transRef);
                Log::info("Updated data amount: " . $this->amount);

            }else{
                $order = new Order();
                $order->user_id = $this->_currentUser->id;
                $order->order_number = '#ORD'.rand(111111,999999);
                $order->total_amount = $amount;
                $order->quantity = $bid;
                $order->order_status = 'pending';
                $order->type = "bid";
                $order->billing_fullName = $this->_currentUser->last_name;
                $order->billing_email = $this->_currentUser->email;
                $order->billing_phone_number = $this->_currentUser->phone_no;
                $order->billing_country = $this->_currentUser->country;
                $order->created_at = Carbon::now();
                $order->save();
                Log::info("New Order: " . $order);

                $transaction = new BidTransaction();
                
                $transaction->user_id = $this->_currentUser->id;
                $transaction->amount = $order->total_amount;
                $transaction->orderID = $order->order_number;
                $transaction->reference =  $transRef;
                $transaction->status = 'pending';
                $transaction->created_at = Carbon::now();
                $transaction->save();
                Log::info("New  Transaction: " . $transaction);

                $this->orderId = $order->order_number;
                $this->transRef = $transaction->reference;
                $this->amount = $order->total_amount;

                Log::info("New data order id: " . $this->orderId);
                Log::info("New data transaction reference: " . $this->transRef);
                Log::info("New data amount: " . $this->amount);
            }
            //make payment            
            if($this->_currentUser->role == UserRoles::FREELANCER){
                $call = 'https://www.globalservicesguppa.com/dashboard/payment/callback';
            }else if($this->_currentUser->role == UserRoles::CLIENT){
                $call = 'https://www.globalservicesguppa.com/client/dashboard/callback';
            }

            $callbackstack = $call;
            $amount = GeneralHelper::Kobo($this->amount);
            Log::info("_________________________________ ");
            Log::info("New data order id: " . $this->orderId);
            Log::info("New data transaction reference: " . $this->transRef);
            Log::info("New data amount: " . $this->amount);
            Log::info("Amount in kobo: " . $amount);
            
             $PaymentData = [
                    "amount" => $amount,
                    "reference" => $this->transRef,
                    'callback_url' =>  $callbackstack,
                    "email" => $this->_currentUser->email,
                    "currency" => "NGN",
                    "order_id" => $this->orderId,
                    "customer" =>  [
                    "first_name" => $this->_currentUser->first_name,
                    "last_name" => $this->_currentUser->last_name,
                    "email" => $this->_currentUser->email,
                    "phone" => $this->_currentUser->phone_no
                    ]
               ];

            Log::info("Data ",$PaymentData);
            $notification = new Notification();
            $notification->user_id = $this->_currentUser->id;
            $notification->title = "Bid purchase";
            $notification->message = "Bid purchase initiated";
            $notification->created_at = Carbon::now();
            $notification->isRead = false;
            $notification->save();
            return new ApiResponseDto(true, "Payment Data ", HttpStatusCode::OK, $PaymentData);

        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }


    public function getWayCallback(Request $request){
        $reference = $request->reference;

        // Fetch payment data from Paystack
        $data = Paystack::getPaymentData();
        dd($data);
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
            return new ApiResponseDto(true,  $msg, HttpStatusCode::OK, $dto);
            
        elseif($data === 'cancelled'):
            return new ApiResponseDto(false, "Payment Canceled ", HttpStatusCode::BAD_REQUEST);
        else:
            return new ApiResponseDto(false, "Payment Failed ", HttpStatusCode::BAD_REQUEST);
        endif;
    }

    public function verifyPayment(Request $request){
       
        $reference = $request->reference;

        // Paystack secret key
        $paystackSecretKey = env('PAYSTACK_SECRET_KEY'); // Make sure you have your secret key in the services config file

        // Send request to Paystack API to verify payment
        $response = Http::withToken($paystackSecretKey)
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        // Check if the request was successful
        if ($response->successful() && $response->json('data.status') === 'success') {
            // Handle successful payment
            // For example, update order status, save payment details to the database, etc.
            return new ApiResponseDto(true, "Payment verified successfully", HttpStatusCode::OK);
         
        }
        return new ApiResponseDto(false, "Payment verification failed", HttpStatusCode::BAD_REQUEST, $response->json('message'));

    }

    public function payForApprovedJob(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'payment_id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return new ApiResponseDto(false, "Validation Error", HttpStatusCode::VALIDATION_ERROR, $validator->errors()->toArray());
            }

            $validate = $validator->validated();
            $payment = PendingApprovedJobPayment::findOrFail($validate['payment_id']);
            $job = AppliedJob::where('id', $payment->applied_id)->first();
            $amount = $payment->amount;
            Log::info("Amount: " . $amount);

            $existingOrder = Order::where(['user_id' => $this->_currentUser->id, 'order_status' => 'pending'])->first();

            Log::info("Existing Order check: " . $existingOrder);
            $transRef = uniqid().time();
            if($existingOrder != null){
                Log::info("Existing Order Is not null: " . $existingOrder);

                $existingOrder->total_amount = $amount;
                $existingOrder->quantity = 1;
                $existingOrder->save();
               
                $existingTrans = GuppaTransaction::where(['order_id' => $existingOrder->id, 'status' => 'pending'])->first();
                Log::info("Existing Transaction check: " . $existingTrans);

                if($existingTrans != null){
                    Log::info("Existing Transaction is not null: " . $existingTrans);

                    $existingTrans->amount = $existingOrder->total_amount;
                    $existingTrans->tnx_ref = $transRef;
                    $existingTrans->save();
                }

                $this->orderId = $existingOrder->id;
                $this->transRef = $transRef;
                $this->amount = $existingOrder->total_amount;
                
                Log::info("Updated data order id: " . $this->orderId);
                Log::info("Updated data transaction reference: " . $this->transRef);
                Log::info("Updated data amount: " . $this->amount);

            }else{
                $order = new Order();
                $order->user_id = $this->_currentUser->id;
                $order->order_number = '#ORD'.rand(111111,999999);
                $order->total_amount = $amount;
                $order->quantity = 1;
                $order->order_status = 'pending';
                $order->type = "job";
                $order->billing_fullName = $this->_currentUser->last_name;
                $order->billing_email = $this->_currentUser->email;
                $order->billing_phone_number = $this->_currentUser->phone_no;
                $order->billing_country = $this->_currentUser->country;
                $order->created_at = Carbon::now();
                $order->save();
                Log::info("New Order: " . $order);

                $transaction = new GuppaTransaction();
                
                $transaction->user_id = $this->_currentUser->id;
                $transaction->amount = $order->total_amount;
                $transaction->order_id = $order->id;
                $transaction->tnx_ref =  $transRef;
                $transaction->type =  "income";
                $transaction->status = 'processing';
                $transaction->guppa_job_id = $job->guppa_job_id;
                $transaction->created_at = Carbon::now();
                $transaction->save();
                Log::info("New  Transaction: " . $transaction);

                $this->orderId = $order->order_number;
                $this->transRef = $transaction->tnx_ref;
                $this->amount = $order->total_amount;

                Log::info("New data order id: " . $this->orderId);
                Log::info("New data transaction reference: " . $this->transRef);
                Log::info("New data amount: " . $this->amount);
            }
            //make payment
            $callbackstack = 'https://www.globalservicesguppa.com/client/dashboard/payment/callback';
            $amount = GeneralHelper::Kobo($this->amount);
            Log::info("_________________________________ ");
            Log::info("New data order id: " . $this->orderId);
            Log::info("New data transaction reference: " . $this->transRef);
            Log::info("New data amount: " . $this->amount);
            Log::info("Amount in kobo: " . $amount);
            
             $PaymentData = [
                    "amount" => $amount,
                    "reference" => $this->transRef,
                    'callback_url' =>  $callbackstack,
                    "email" => $this->_currentUser->email,
                    "currency" => "NGN",
                    "order_id" => $this->orderId,
                    "customer" =>  [
                    "first_name" => $this->_currentUser->first_name,
                    "last_name" => $this->_currentUser->last_name,
                    "email" => $this->_currentUser->email,
                    "phone" => $this->_currentUser->phone_no
                    ]
               ];
            Log::info("Data ",$PaymentData);

            $notification = new Notification();
            $notification->user_id = $this->_currentUser->id;
            $notification->title = "Job Payment";
            $notification->message = "Job payment initiated";
            $notification->created_at = Carbon::now();
            $notification->isRead = false;
            $notification->save();
            return new ApiResponseDto(true, "Payment Data ", HttpStatusCode::OK, $PaymentData);

        }catch(\Exception $e){
            return new ApiResponseDto(false, "Server Error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }
  
    public function verifyGuppaPayment(Request $request){
       
        $reference = $request->reference;

        // Paystack secret key
        $paystackSecretKey = env('PAYSTACK_SECRET_KEY'); // Make sure you have your secret key in the services config file

        // Send request to Paystack API to verify payment
        $response = Http::withToken($paystackSecretKey)
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        // Check if the request was successful
        if ($response->successful() && $response->json('data.status') === 'success') {
            $p = GuppaTransaction::where('tnx_ref', $reference)->first();
            Log::info("transaction verify guppa payment", [$p]);
            if($p != null){
                $applied = AppliedJob::where('guppa_job_id', $p->guppa_job_id)->where('status', 'approved')->first();
                Log::info("applied verify guppa payment", [$applied]);
                if($applied){
                  $py = PendingApprovedJobPayment::where('applied_id', $applied->id)->first();
                  Log::info("pending payment verify guppa payment", [$py]);
                  if($py != null){
                    $py->tnx_ref = $reference;
                    $py->save();
                    Log::info("pending tnx updated verify guppa payment", [$py->tnx_ref]);
                  }
                }
            }
            // Handle successful payment
            // For example, update order status, save payment details to the database, etc.
            return new ApiResponseDto(true, "Payment verified successfully", HttpStatusCode::OK);
         
        }
        return new ApiResponseDto(false, "Payment verification failed", HttpStatusCode::BAD_REQUEST, $response->json('message'));

    }

    public function pendingPayment(){
        if(UserRoleHelper::isClient($this->_currentUser)){
            $pendingPayment =PendingApprovedJobPayment::where('status', 'pending')->where('client_id', $this->_currentUser->id)->orderBy('created_at', 'desc')->get();
            
        }else{
            $pendingPayment = PendingApprovedJobPayment::orderBy('created_at', 'desc')->get();
        }
        if($pendingPayment->isNotEmpty()){
            $dto =  $pendingPayment->map(function($p){
                 return new PendingApprovedJobPaymentResponseDto($p);
             });
             return new ApiResponseDto(true, "Pending Payments", HttpStatusCode::OK, $dto->toArray());
 
         }else{
             return new ApiResponseDto(true, "No Pending Payments", HttpStatusCode::OK);
 
         }
    }
}
