<?php

namespace App\Domain\Entities;

use Carbon\Carbon;
use App\Models\User;
use App\Models\GuppaJob;
use App\Helpers\GeneralHelper;
use App\Models\AppliedJob;
use App\Models\JobTag;
use App\Models\Tag;

class GuppaJobEntity
{
    private int $job_id;
    private int $client_id;
    private string $title;
    private string $slug;
    private string $description;
    private  $tags;
    private float $amount;
    private string $time;
    private string $bid_points;
    private string $project_type;
    private  $experience_level;
    private  $required_skills;
    private string $job_status;
    private string $visibility;
    private  $dateCreated;
    private  $dateModified;
    private $client_details;
    private $time_limit = null;
    private $applications;
    private $category;

    public function __construct(GuppaJob $job){
        $this->job_id = $job->id;
        $this->client_id = $job->user_id;
        $this->title = $job->title;
        $this->slug = $job->slug;
        $this->description = $job->description;
        $this->tags = $this->grabTags($job->id);
        $this->amount = $job->amount;
        $this->time = $job->time;
        $this->bid_points = $job->bid_points;
        $this->project_type = $job->project_type;
        $this->experience_level = $job->experience_level;
        $this->required_skills = $job->required_skills;
        $this->job_status = $job->job_status;
        $this->visibility = $job->visibility;
        $this->dateCreated = $job->created_at;
        $this->dateModified = $job->updated_at;
        $this->client_details = $this->getClientDetailsById($job->user_id);
        $this->time_limit = $job->time_limit;
        $this->applications = $this->getJobApplications($job->id);
        $this->category = $job->category;


    }

    public function grabTags($jobId){
        $tags = JobTag::where('guppa_job_id', $jobId)->get();
        if($tags->isNotEmpty()){
            $dto = $tags->map(function($t){
                $name = Tag::where('id', $t->tag_id)->first();
                return [
                    'tag' => $name->tag,
                    'slug' => $name->slug
                ];
            });
            return $dto;
        }else{
            return [];
        }
    }


    public function getCategory(){
        return $this->category;
    }
    public function getApplications(){
        return $this->applications;
    }
    public function getJobApplications($jobId){
        $applications = AppliedJob::where('guppa_job_id', $jobId)->orderBy('created_at', 'desc')->get();
        if($applications->isNotEmpty()){
                $applications = $applications->map(function($app) {
                    return [
                        'id' => $app->id,
                        'created_at' => $app->created_at,
                        'freelancers' => $this->getFreelancerDetailsById($app->user_id),
                        
                    ];
                })->toArray();
            return $applications;
        }
        return null;
    }

    public function getJobId(){
        return $this->job_id;
    }

    //client id
    public function getClientId(){
        return $this->client_id;
    }

    //title
    public function getTitle(){
        return $this->title;
    }

    //description
    public function getDescription(){
        return $this->description;
    }

    //slug
    public function getSlug(){
        return $this->slug;
    }

    public function getTags(){
        return $this->tags;
    }

    public function getAmount(){
        return $this->amount;
    }

    public function getTime(){
        return $this->time;
    }

    public function getBidPoints(){
        return $this->bid_points;
    }

    public function getProjectType(){
        return $this->project_type;
    }
    public function getExperienceLevel(){
        return $this->experience_level;
    }

    public function getRequiredSkills(){
        return $this->required_skills;
    }
    // job_status
    public function getJobStatus(){
        return $this->job_status;
    }
    // visibility
    public function getVisibility(){
        return $this->visibility;
    }
    // dateCreated
    public function getDateCreated(){
        return $this->dateCreated;
    }
    // dateModified
    public function getDateModified(){
        return $this->dateModified;
    }

    public function getTimeLimit(){
        return $this->time_limit;
    }
    // client
    public function getClientDetails(){
        return $this->client_details;
    }

    public function getClientDetailsById($client_id){
            $client = User::findOrFail($client_id);
            if($client != null){
                return [
                    'client_id' => $client_id,
                    'name' => $client->first_name . " " . $client->last_name,
                    'profile_photo' => asset('storage/app/public/uploads/'.$client->profile_photo),
                    'chat_id' => $client->chatId
                ];
          }
    }
    public function getFreelancerDetailsById($userId){
            $user = User::findOrFail($userId);
            if($user != null){
                return [
                    'name' => $user->first_name . " " . $user->last_name,
                    'profile_photo' => asset('storage/app/public/uploads/'.$user->profile_photo),
                    'chat_id' => $user->chatId,
                    'role' => $user->role,
                    'email' => $user->email
                ];
          }
          return null;
    }

}
