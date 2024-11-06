<?php

 namespace App\Domain\DTOs\Response\Transactions;

use App\Domain\Entities\PaymentsEntity;
use App\Domain\Entities\TransactionEntity;
use App\Models\GuppaTransaction;

class PaymentsResponseDto
{
    public $user_id;
    public $reference;
    public $amount;
    public $orderId;
    public $status;
    public $created_at;
    public $updated_at;
    public $user;

    public function __construct(PaymentsEntity $transaction){
        $this->user_id = $transaction->getUserId();
        $this->reference = $transaction->getTransactionRef();
        $this->amount = $transaction->getAmount();
        $this->orderId = $transaction->getOrderId();
        $this->status = $transaction->getStatus();
        $this->created_at = $transaction->getCreatedAt();
        $this->updated_at = $transaction->getUpdatedAt();
        $this->user = $transaction->getUser();
    }


    public function toArray(){
        return [
            'user_id' => $this->user_id,
            'reference' => $this->reference,
            'amount' => $this->amount,
            'orderId' => $this->orderId,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => $this->user
        ];
    }

}
