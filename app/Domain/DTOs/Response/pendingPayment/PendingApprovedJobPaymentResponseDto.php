<?php
 namespace App\Domain\DTOs\Response\pendingPayment;
 use App\Models\PendingApprovedJobPayment;
 use App\Models\User;
 use App\Models\AppliedJob;


class PendingApprovedJobPaymentResponseDto
{
    public int $id;
    public int $client_id;
    public int $applied_id;
    public $amount;
    public $status;
    public $created_at;
    public $date_paid;
    public $client;
    public $job_title;
    public $tnx_ref;

    public function __construct(PendingApprovedJobPayment $payment)
    {
        $this->id = $payment->id;
        $this->client_id = $payment->client_id;
        $this->applied_id = $payment->applied_id;
        $this->amount = $payment->amount;
        $this->status = $payment->status;
        $this->created_at = $payment->created_at;
        $this->date_paid = $payment->date_paid;
        $this->client = $this->getClient($payment->client_id);
        $this->job_title = $payment->job_title;
        $this->tnx_ref = $payment->tnx_ref;

    }


    public function getClient($id){
        $client =  User::where('id',$id)->first();
        
        return [
            'name' => $client->last_name . " " . $client->first_name,
            'email' => $client->email,
            'profile_pic' => asset('storage/app/public/uploads/'.$client->profile_photo)
        ];
    }

    public function toArray(){
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'applied_id' => $this->applied_id,
            'amount' => $this->amount,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'date_paid' => $this->date_paid,
            'client' => $this->client,
            'job_title' => $this->job_title,
            'tnx_ref' => $this->tnx_ref
        ];
    }

    
}