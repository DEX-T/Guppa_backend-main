<?php

 namespace App\Domain\DTOs\Request\Country;

class CountryRequestDto
{
    public string $country;
    public string $short_code;
    public int $country_id;
    public function __construct(string $country, string $short_code, int $country_id = 0){
        $this->country = $country;
        $this->short_code = $short_code;
        $this->country_id = $country_id;
    }
    // Define your DTO properties and methods here
}