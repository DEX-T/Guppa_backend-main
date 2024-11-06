<?php

 namespace App\Domain\DTOs\Response\Invites;

use App\Models\GuppaJob;

class InviteOnlyJobResponseDto
{
    public int $job_id;
    public string $visibility;
    public string $title;

    public function __construct(GuppaJob $job){
        $this->job_id = $job->id;
        $this->visibility = $job->job_visibility;
        $this->title = $job->title;
    }

    public function toArray(): array
    {
        return  [
            'job_id' => $this->job_id,
            'title' => $this->title,
            'visibility' => $this->visibility
        ];
    }
    
}