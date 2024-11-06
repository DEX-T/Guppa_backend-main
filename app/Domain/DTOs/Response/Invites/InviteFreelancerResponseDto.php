<?php

 namespace App\Domain\DTOs\Response\Invites;

use App\Domain\Entities\InviteFreelancerEntity;

class InviteFreelancerResponseDto
{
    public $id;
    public $freelancer_id;
    public $project_id;
    public $description;
    public $status;
    public $created_at;
    public $freelancer;
    public $job;

    public function __construct(InviteFreelancerEntity $invite){
        $this->id = $invite->id;
        $this->freelancer_id = $invite->freelancer_id;
        $this->project_id = $invite->project_id;
        $this->job = $invite->job;
        $this->description = $invite->description;
        $this->status = $invite->status;
        $this->created_at = $invite->created_at;
        $this->freelancer = $invite->freelancer;
    }


    public function toArray(){
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'job' => $this->job,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'freelancer_id' => $this->freelancer_id,
            'freelancer' => $this->freelancer,
            
        ];
    }
   
}