<?php
namespace App\Domain\DTOs\Response\Country;

use DateTime;
use App\Domain\Entities\CountryEntity;

class CountryResponseDto
{
    public int $country_id;
    public string $country;
    public string $short_code;
    public  $create_at;
    public  $update_at;
    public string $status; 

    
    public function __construct(CountryEntity $country) {
        $this->country_id = $country->getCountryId();
        $this->country = $country->getCountry();
        $this->short_code = $country->getShortCode();
        $this->create_at = $country->getCreatedAt();
        $this->update_at = $country->getUpdatedAt();
        $this->status = $country->getStatus();
  
      }
  
      
    public function toArray(){
      return [
          'country_id' => $this->country_id,
          'country' => $this->country,
          'short_code' => $this->short_code,
          'create_at' => $this->create_at,
          'update_at' => $this->update_at,
          'status' => $this->status
          ];
  }
}