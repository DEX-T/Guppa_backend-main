<?php

 namespace App\Domain\DTOs\Response\Jobs;

use App\Models\Tag;
use App\Models\User;
use App\Models\JobTag;
use App\Models\GuppaJob;
use App\Models\AppliedJob;
use App\Domain\Entities\ContractEntity;
use App\Domain\Entities\GuppaJobEntity;

class ContractResponseDto
{
    public  $contract_id;
    public  $guppa_job_id;
    public  $user_id;
    public  $client_id;
    public  $applied_job_id;
    public  $progress;
    public  $total_earnings;
    public  $status;
    public  $created_at;
    public $milestone;
    public $client;
    public $contract_rate;
    public $payable;
    public $job;
    public $applied_detail;

    public function __construct(ContractEntity $job){
        $this->contract_id = $job->getContractId();
        $this->guppa_job_id = $job->getJobId();
        $this->user_id = $job->getUserId();
        $this->client_id = $job->getClientId();
        $this->applied_job_id = $job->getAppliedJobId();
        $this->progress = $job->getProgress();
        $this->total_earnings = $job->getTotalEarnings();
        $this->status = $job->getStatus();
        $this->created_at = $job->getCreatedAt();
        $this->milestone = $job->getMilestone();
        $this->client = $this->getClientDetail($job->getClientId());
        $this->contract_rate = $this->getJobAmount($job->getJobId());
        $this->payable = $this->getPayableAmount($job->getAppliedJobId());
        $this->job = $this->getJobDetail($job->getJobId());
        $this->applied_detail = $job->getAppliedDetail();
        
    }


    public function getClientDetail($clientId){
        $client = User::where('id', $clientId)->first();
        return [
            'id' => $client->id,
            'name' => $client->last_name . " " . $client->first_name,
            'profile_photo' => asset('storage/app/public/uploads/' . $client->profile_photo),
            'email' => $client->email,
            'chat_id' => $client->chatId
        ];
    }

    public function getJobAmount($jobId){
        return GuppaJob::where('id', $jobId)->first()->amount;
    }

    public function getPayableAmount($applied_job_id){
        return AppliedJob::where('id', $applied_job_id)->first()->total_amount_payable;
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
    


    public function toArray(){
        return [
            'contract_id' => $this->contract_id,
            'guppa_job_id' => $this->guppa_job_id,
            'user_id' => $this->user_id,
            'client_id' => $this->client_id,
            'applied_job_id' => $this->applied_job_id,
            'progress' => $this->progress,
            'total_earnings' => $this->total_earnings,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'milestone' => $this->milestone,
            'client' => $this->client,
            'contract_rate' => $this->contract_rate,
            'payable' => $this->payable,
            'job' => $this->job,
            'applied_detail' =>  $this->applied_detail,

        ];
    }
}