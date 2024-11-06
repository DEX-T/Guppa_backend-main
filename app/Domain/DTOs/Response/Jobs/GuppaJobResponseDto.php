<?php

 namespace App\Domain\DTOs\Response\Jobs;

use App\Domain\Entities\GuppaJobEntity;
use App\enums\UserRoles;
use App\Helpers\GeneralHelper;
use App\Models\GuppaJob;
use App\Models\User;
use Carbon\Carbon;

class GuppaJobResponseDto
{
    public int $job_id;
    public int $client_id;
    public string $title;
    public string $slug;
    public string $description;
    public  $tags;
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
    public  $time_limit = null;
    public $applications;
    public $category;

    public function __construct(GuppaJobEntity $job){
        $this->job_id = $job->getJobId();
        $this->client_id = $job->getClientId();
        $this->title = $job->getTitle();
        $this->slug = $job->getSlug();
        $this->description = $job->getDescription();
        $this->tags = $job->getTags();
        $this->amount = $job->getAmount();
        $this->time = $job->getTime();
        $this->bid_points = $job->getBidPoints();
        $this->project_type = $job->getProjectType();
        $this->required_skills = $job->getRequiredSkills();
        $this->experience_level = $job->getExperienceLevel();
        $this->job_status = $job->getJobStatus();
        $this->time_limit = $job->getTimeLimit();
        $this->visibility = $job->getVisibility();
        $this->dateCreated =   GeneralHelper::timeAgo($job->getDateCreated());
        $this->dateModified =  GeneralHelper::timeAgo($job->getDateModified());
        $this->client_details = $job->getClientDetails();
        $this->applications = $job->getApplications();
        $this->category = $job->getCategory();


    }


    public function toArray(): array
    {
        return  [
            'job_id' => $this->job_id,
            'client_id' => $this->client_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'tags' => $this->tags,
            'amount' => $this->amount,
            'time' => $this->time,
            'bid_points' => $this->bid_points,
            'project_type' => $this->project_type,
            'experience_level' => $this->experience_level,
            'time_limit' => $this->time_limit,
            'required_skills' => $this->required_skills,
            'job_status' => $this->job_status,
            'visibility' => $this->visibility,
            'dateCreated' =>$this->dateCreated,
            'dateModified' => $this->dateModified,
            'client_details' => $this->client_details,
            'job_applications' => $this->applications,
            'category' => $this->category
            
            

        ];
    }
}
