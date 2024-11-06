<?php

namespace App\Domain\Entities;

use App\Models\Country;
use DateTime;

class CountryEntity
{
    private int $country_id;
    private string $country;
    private string $short_code;
    private  $create_at;
    private  $update_at;
    private string $status; 

    
    public function __construct(Country $country) {
        $this->country_id = $country->id;
        $this->country = $country->country;
        $this->short_code = $country->short_code;
        $this->create_at = $country->created_at;
        $this->update_at = $country->updated_at;
        $this->status = $country->status;
  
      }
  
      public function getCountry(){
        return $this->country;
      }
  
  
      public function getCountryId(){
        return $this->country_id;
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

      //get short code
      public function getShortCode(){
        return $this->short_code;
        }
  
   
}