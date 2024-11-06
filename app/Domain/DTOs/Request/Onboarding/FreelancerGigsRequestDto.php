<?php

namespace App\Domain\DTOs\Request\Onboarding;

class FreelancerGigsRequestDto
{
    public string $name;
    public string $description;
    public string $status;

    public function __construct(array $data){
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->status = $data['status'];

    }
    // Define your DTO properties and methods here
}
