<?php

 namespace App\Domain\DTOs\Response\Transactions;

use App\Domain\Entities\TransactionEntity;
use App\Helpers\GeneralHelper;
use App\Models\GuppaTransaction;

class EarningResponseDto
{
    public $user_id;
   public $total_income;
   public $total_payouts;
    public function __construct(GuppaTransaction $transaction){
        $this->user_id = $transaction->user_id;
        $this->total_income = $this->getTotalAmount($transaction,"income");
        $this->total_payouts =  $this->getTotalAmount($transaction,"withdrawal");

    }

    public function getTotalAmount($transaction, $type){
        $total = 0.0;
        if($type == "income"){
            $total = $transaction->where(['type' => "income"])->sum('amount');
        }else if($type == 'withdrawal'){
            $total = $transaction->where(['type' => "withdrawal"])->sum('amount');
        }
        return GeneralHelper::formatAmount($total, "USD");
    }

    public function toArray(){
        return [
            'user_id' => $this->user_id,
            'total_income' => $this->total_income,
            'total_payouts' => $this->total_payouts,
        ];
    }

}
