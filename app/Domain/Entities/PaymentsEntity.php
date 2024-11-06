<?php

namespace App\Domain\Entities;

use App\Models\BidTransaction;
use App\Models\User;
use App\Models\GuppaTransaction;

class PaymentsEntity
{
    private $user_id;
    private $reference;
    private $amount;
    private $orderId;
    private $status;
    private $created_at;
    private $updated_at;
    private $user;

    public function __construct(BidTransaction $transaction){
        $this->user_id = $transaction->user_id;
        $this->reference = $transaction->reference;
        $this->amount = $transaction->amount;
        $this->orderId = $transaction->orderId;
        $this->status = $transaction->status;
        $this->created_at = $transaction->created_at;
        $this->updated_at = $transaction->updated_at;
        $this->user = $this->getUserDetail($transaction->user_id);
    }

    public function getUserDetail($userId){
        $user =  User::where('id', $userId)->first();
        return [
            'name' => $user->last_name . " " . $user->first_name,
            'email' => $user->email,
            'profile_pic' => asset('storage/app/public/uploads/'.$user->profile_photo),
            'role' => $user->role

        ];
    }
    //get user ii
    public function getUser()
    {
        return $this->user;
    }
    public function getUserId()
    {
        return $this->user_id;
    }

    public function getOrderId(){
        return $this->orderId;
    }

    public function getTransactionRef(){
        return $this->reference;
    }

    public function getAmount(){
        return $this->amount;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }


}
