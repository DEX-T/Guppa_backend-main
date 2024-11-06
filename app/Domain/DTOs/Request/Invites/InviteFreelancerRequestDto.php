<?php

 namespace App\Domain\DTOs\Request\Invites;

class InviteFreelancerRequestDto
{
   
    public $client_id;
    public $skills;
    public $ratings;
    public $experience;
    public $job_id;

    public function __construct(array $validated, $clientId){
        $this->client_id = $clientId;
        $this->skills = $validated['skills'];
        $this->ratings = $validated['ratings'];
        $this->experience = $validated['experience'];
        $this->job_id = $validated['job_id'];
    }
    
}