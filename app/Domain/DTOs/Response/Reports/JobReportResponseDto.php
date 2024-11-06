<?php

 namespace App\Domain\DTOs\Response\Reports;

use App\Domain\Entities\GuppaJobEntity;
use App\Helpers\GeneralHelper;

class JobReportResponseDto
{
    public string $title;
    public float $amount;
    public string $time;
    public string $bid_points;
    public string $project_type;
    public  $experience_level;
    public  $required_skills;
    public string $job_status;
    public string $visibility;
    public  $dateCreated;
    public  $dateModified;
    public $client_details;

    public function __construct(GuppaJobEntity $job){
        $this->title = $job->getTitle();
        $this->amount = $job->getAmount();
        $this->time = $job->getTime();
        $this->bid_points = $job->getBidPoints();
        $this->project_type = $job->getProjectType();
        $this->required_skills = $job->getRequiredSkills();
        $this->experience_level = $job->getExperienceLevel();
        $this->job_status = $job->getJobStatus();
        $this->visibility = $job->getVisibility();
        $this->dateCreated =   GeneralHelper::timeAgo($job->getDateCreated());
        $this->dateModified =  GeneralHelper::timeAgo($job->getDateModified());
        $this->client_details = $job->getClientDetails();


    }


    public function toArray(): array
    {
        return  [
            'title' => $this->title,
            'amount' => $this->amount,
            'time' => $this->time,
            'bid_points' => $this->bid_points,
            'project_type' => $this->project_type,
            'experience_level' => $this->experience_level,
            'required_skills' => $this->required_skills,
            'job_status' => $this->job_status,
            'visibility' => $this->visibility,
            'dateCreated' =>$this->dateCreated,
            'dateModified' => $this->dateModified,
            'client_details' => $this->client_details

        ];
    }
}
