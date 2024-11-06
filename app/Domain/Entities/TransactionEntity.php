<?php

namespace App\Domain\Entities;

use App\Models\User;
use App\Models\GuppaTransaction;

class TransactionEntity
{
    private $user_id;
    private $guppa_job_id;
    private $tnx_ref;
    private $amount;
    private $type;
    private $status;
    private $date_created;
    private $user;

    public function __construct(GuppaTransaction $transaction){
        $this->user_id = $transaction->user_id;
        $this->guppa_job_id =$transaction->guppa_job_id;
        $this->tnx_ref = $transaction->tnx_ref;
        $this->amount = $transaction->amount;
        $this->type = $transaction->type;
        $this->status = $transaction->status;
        $this->date_created = $transaction->created_at;
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

    public function getJodId(){
        return $this->guppa_job_id;
    }

    public function getTransactionRef(){
        return $this->tnx_ref;
    }

    public function getAmount(){
        return $this->amount;
    }

    public function  getType()
    {
        return $this->type;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDate()
    {
        return $this->date_created;
    }


}
