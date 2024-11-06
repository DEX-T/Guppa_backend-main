<?php

 namespace App\Domain\DTOs\Request\Bid;

use App\Models\BidPaymentConfig;

class BidRequestDto
{
    public  int $bid;
    public int $amount;
    public function __construct(int $bid){
        $this->bid = $bid;
        $this->amount = $this->getTotalAmount();
    }

    //get bid amount from db

    public function getBidCharge(){
        //get bid charge from db
        $charge = BidPaymentConfig::findOrFail(1);
        return floor($charge->amount);
    }
    public function getTotalAmount(){
        return $this->bid * $this->getBidCharge();
    }
    
}