<?php

namespace App\Domain\DTOs\Request\YearsOfExperience;

class UpdateYearsOfExperienceRequestDto
{
    public int $id;
    public string  $yearOfExperience;


    public function __construct(int $id, string $yearOfExperience)
    {
        $this->id = $id;
        $this->yearOfExperience = $yearOfExperience;

    }

}
