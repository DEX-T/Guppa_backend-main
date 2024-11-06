<?php

namespace App\Domain\Entities;

use App\Models\Bid;
use App\Models\User;
use App\Models\BidPaymentConfig;

class BidEntity
{
    private int $id;
    private int $bid;
    private $created_at;
    private $updated_at;
    private $user;
    private $user_id;
    private $unit_price;
    
    public function __construct(Bid $bid){
        $this->id = $bid->id;
        $this->bid = $bid->bid;
        $this->created_at = $bid->created_at;
        $this->updated_at = $bid->updated_at;
        $this->user_id = $bid->user_id;
        $this->user = $this->grabUser($bid->user_id);
        $this->unit_price = $this->getUnitPrice();
    }
   
    public function getUnitPrice(){
        $charge = BidPaymentConfig::findOrFail(1);
        
        return floor($charge->amount);
    }


    public function getPrice(){
        return $this->unit_price;
    }

   

    public function getUserId(){
        return $this->user_id;
    }
    public function getBid(){
        return $this->bid;
    }

    public function getId(){
        return $this->id;
    }

    public function getCreatedAt(){
        return $this->created_at;
    }

    public function getUpdatedAt(){
        return $this->updated_at;
    }

    public function getUser(){
        return $this->user;
    }

    public function grabUser($userId){
        $user = User::where('id', $userId)->first();
        return [
            'user_id' => $user->id,
            'name' => $user->last_name . " " . $user->first_name,
            'email' => $user->email,
            'profile_photo' => asset('storage/app/public/uploads/'.$user->profile_photo) 
        ];
    }
}