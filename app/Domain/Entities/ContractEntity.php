<?php

namespace App\Domain\Entities;

use App\Models\Tag;
use App\Models\MyJob;
use App\Models\JobTag;
use App\Models\GuppaJob;
use App\Models\Milestone;
use App\Models\AppliedJob;

class ContractEntity
{
    private  $contract_id;
    private  $guppa_job_id;
    private  $user_id;
    private  $client_id;
    private  $applied_job_id;
    private  $progress;
    private  $total_earnings;
    private  $status;
    private  $created_at;
    private $milestone;
    private $total_hours_worked;
    private $applied_detail;
    private $job;

   

    public function __construct(MyJob $job){
        $this->contract_id = $job->id;
        $this->guppa_job_id = $job->guppa_job_id;
        $this->user_id = $job->user_id;
        $this->client_id = $job->client_id;
        $this->applied_job_id = $job->applied_job_id;
        $this->progress = $job->progress;
        $this->total_earnings = $job->total_earnings != 0 ? $job->total_earnings : 0.0;
        $this->status = $job->status;
        $this->created_at = $job->created_at;
        $this->milestone = $this->getMilestonesDetail($job->applied_job_id);
        $this->total_hours_worked = $job->total_hours_worked;
        $this->applied_detail = $this->getAppliedDetails($job->applied_job_id);
        $this->job = $this->getJobDetail($job->guppa_job_id);
        
    }

    public function getJob(){
        return $this->job;
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

    public function getJobDetail($job_id){
        $job =  GuppaJob::where('id',$job_id)->first();
        return [
            'id' => $job->id,
            'title' => $job->title,
            'description' => $job->description,
            'project_type' => $job->project_type,
            'tags' =>  $this->grabTags($job_id),
           'amount' => $job->amount,
           'time' => $job->time,
           'required_skills' => $job->required_skills,
           'category' => $job->category

        ];
    }
  
    public function getAppliedDetail(){
        return $this->applied_detail;
    }

    public function getMilestone(){
        return $this->milestone;
    }
   
    public function getContractId(){
        return $this->contract_id;
    }
    
    public function getStatus(){
        return $this->status;
    }

    public function getClientId(){
        return $this->client_id;
    }

    public function getAppliedJobId(){
        return $this->applied_job_id;
    }

    public function getProgress() {
        return $this->progress;
    }

    public function getTotalEarnings(){
        return $this->total_earnings;
    }

    public function getCreatedAt(){
        return $this->created_at;
    }

    //get guppa job id 
    public function getJobId(){
        return $this->guppa_job_id;
    }

    //get user id
    public function getUserId(){
        return $this->user_id;
    }

    public function getTotalHoursWorked(){
        return $this->total_hours_worked;
    }

    public function getMilestonesDetail($job_id)
    {
        $milestones = Milestone::where('applied_job_id', $job_id)->get();
        $milestonesArray = $milestones->map(function ($milestone) {
            return [
                'id' => $milestone->id,
                'description' => $milestone->milestone_description,
                'amount' => $milestone->milestone_amount,
                'status' => $milestone->status,
                'created_at' => $milestone->created_at,
            ];
        })->toArray();
    
        return $milestonesArray;
    }

    public function getAppliedDetails($applied_job_id)
    {
        $applied = AppliedJob::where('id', $applied_job_id)->first();
        $appliedArray = [
            'applied_id' => $applied->id,
            'bid_point' => $applied->bid_point,
            'total_amount_payable' => $applied->total_amount_payable,
            'project_timeline' => $applied->project_timeline,
            'payment_type' => $applied->payment_type,
            'project_price' => $applied->project_price,
            'total_milestone_price' => $applied->total_milestone_price,
            ];
       
        return $appliedArray;
    }
   
    
}