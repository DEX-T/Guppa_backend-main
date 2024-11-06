<?php

 namespace App\Domain\DTOs\Response\Transactions;

use App\Domain\Entities\TransactionEntity;
use App\Models\GuppaTransaction;

class TransactionResponseDto
{
    public $user_id;
    public $guppa_job_id;
    public $tnx_ref;
    public $amount;
    public $type;
    public $status;
    public $date_created;
    public $user;

    public function __construct(TransactionEntity $transaction){
        $this->user_id = $transaction->getUserId();
        $this->guppa_job_id =$transaction->getJodId();
        $this->tnx_ref = $transaction->getTransactionRef();
        $this->amount = $transaction->getAmount();
        $this->type = $transaction->getType();
        $this->status = $transaction->getStatus();
        $this->date_created = $transaction->getDate();
        $this->user = $transaction->getUser();
    }


    public function toArray(){
        return [
            'user_id' => $this->user_id,
            'guppa_job_id' => $this->guppa_job_id,
            'tnx_ref' => $this->tnx_ref,
            'amount' => $this->amount,
            'type' => $this->type,
            'status' => $this->status,
            'date_created' => $this->date_created,
            'user' => $this->user
        ];
    }

}
