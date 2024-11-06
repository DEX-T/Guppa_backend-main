<?php

namespace App\Domain\Entities;

use App\Models\Prefix;
use DateTime;

class PrefixEntity
{
    private int $prefix_id;
    private  string $prefix;
    private DateTime $create_at;
    private DateTime $update_at;
    private string $status;
   


    public function __construct(Prefix $prefix) {
      $this->prefix = $prefix->prefix;
      $this->prefix_id = $prefix->id;
      $this->create_at = $prefix->created_at;
      $this->update_at = $prefix->updated_at;
      $this->status = $prefix->status;
      
    }
  
    public function getPrefix(){
      return $this->prefix; 
    }
  
    public function getPrefixId(){
      return $this->prefix_id;
    }
  
    public function getStatus(){
        return $this->status;
    }

    public function getCreatedAt(){
        return $this->create_at;
    }

    public function getUpdatedAt(){
        return $this->update_at;
    }
   
    // Define your entity properties and methods here
}