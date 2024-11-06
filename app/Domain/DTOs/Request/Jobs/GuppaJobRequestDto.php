<?php

 namespace App\Domain\DTOs\Request\Jobs;

class GuppaJobRequestDto
{
    public int $job_id;
    public int $client_id;
    public string $title;
    public string $description;
    public string $tags;
    public float $amount;
    public string $time;
    public string $project_type;
    public  $experience_level;
    public  $required_skills;
    public  $total_hour = 0;
    public  $category;


    public function __construct( array $data){
        $this->job_id = $data['job_id'];
        $this->client_id = $data['client_id'];
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->tags = $data['tags'];
        $this->amount = $data['amount'];
        $this->time = $data['time'];
        $this->project_type = $data['project_type'];
        $this->experience_level = $data['experience_level'];
        $this->required_skills = $data['required_skills'];
        $this->total_hour = $data['total_hour'];
        $this->category = $data['category'];

    }
    // Define your DTO properties and methods here
}
