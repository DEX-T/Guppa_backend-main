<?php

namespace App\Domain\DTOs\Request\YearsOfExperience;

class YearsOfExperienceRequestDto
{
    public string $yearOfExperience;

    public function __construct( string $yearOfExperience)
    {
        $this->yearOfExperience = $yearOfExperience;


    }
}
