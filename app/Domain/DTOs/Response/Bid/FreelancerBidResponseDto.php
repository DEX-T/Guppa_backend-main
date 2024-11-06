<?php
 namespace App\Domain\DTOs\Response\Bid;
 use App\Domain\Entities\BidEntity;

class FreelancerBidResponseDto
{
    public int $bid;
    public $unit_price;
   
    
    public function __construct(BidEntity $bid){
        $this->bid = $bid->getBid();
        $this->unit_price = $bid->getPrice();
    }


    public function toArray(){
        return [
            'bid' => $this->bid,
            'unit_price' => $this->unit_price
        ];
    }
    
}