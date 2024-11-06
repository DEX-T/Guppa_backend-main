<?php

 namespace App\Domain\DTOs\Response\Bid;

use App\Domain\Entities\BidEntity;

class BidResponseDto
{
    public int $id;
    public int $bid;
    public $created_at;
    public $updated_at;
    public $user;
    public $user_id;
    
    public function __construct(BidEntity $bid){
        $this->id = $bid->getId();
        $this->bid = $bid->getBid();
        $this->created_at = $bid->getCreatedAt();
        $this->updated_at = $bid->getUpdatedAt();
        $this->user_id = $bid->getUserId();
        $this->user = $bid->getUser();
    }


    public function toArray(){
        return [
            'id' => $this->id,
            'bid' => $this->bid,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_id' => $this->user_id,
            'user' => $this->user
        ];
    }
    
}