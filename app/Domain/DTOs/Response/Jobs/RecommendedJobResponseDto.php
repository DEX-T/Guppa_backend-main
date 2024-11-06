<?php

 namespace App\Domain\DTOs\Response\Jobs;

use App\Domain\Entities\GuppaJobEntity;
use App\enums\UserRoles;
use App\Helpers\GeneralHelper;
use App\Models\GuppaJob;
use App\Models\User;
use Carbon\Carbon;

class RecommendedJobResponseDto
{
    public int $job_id;
    public string $title;
    public string $slug;
    public float $amount;
    public  $dateCreated;
    public  $dateModified;
   

    public function __construct(GuppaJobEntity $job){
        $this->job_id = $job->getJobId();
        $this->title = $job->getTitle();
        $this->slug = $job->getSlug();
        $this->amount = $job->getAmount();
        $this->dateCreated =   GeneralHelper::timeAgo($job->getDateCreated());
        $this->dateModified =  GeneralHelper::timeAgo($job->getDateModified());


    }


    public function toArray(): array
    {
        return  [
            'job_id' => $this->job_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'amount' => $this->amount,
            'dateCreated' =>$this->dateCreated,
            'dateModified' => $this->dateModified

        ];
    }
}
