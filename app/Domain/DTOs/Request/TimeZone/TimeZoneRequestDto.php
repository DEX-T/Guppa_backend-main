<?php

 namespace App\Domain\DTOs\Request\TimeZone;

class TimeZoneRequestDto
{
    public string $latitude;
    public string $longitude;
    public function __construct(string $latitude,string $longitude){
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    // Define your DTO properties and methods here
}
