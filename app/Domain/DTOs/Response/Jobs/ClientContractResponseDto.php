<?php

 namespace App\Domain\DTOs\Response\Jobs;

use App\Models\User;
use App\Models\GuppaJob;
use App\Domain\Entities\ContractEntity;

class ClientContractResponseDto
{
    public  $contract_id;
    public  $guppa_job_id;
    public  $user_id;
    public  $applied_job_id;
    public  $progress;
    public  $status;
    public  $created_at;
    public $milestone;
    public $contract_rate;
    public $freelancer;
    public $applied_detail;
    public $job;


    public function __construct(ContractEntity $job){
        $this->contract_id = $job->getContractId();
        $this->guppa_job_id = $job->getJobId();
        $this->user_id = $job->getUserId();
        $this->applied_job_id = $job->getAppliedJobId();
        $this->progress = $job->getProgress();
        // $this->total_earnings = $job->getTotalEarnings();
        $this->status = $job->getStatus();
        $this->created_at = $job->getCreatedAt();
        $this->milestone = $job->getMilestone();
        $this->contract_rate = $this->getJobAmount($job->getJobId());
        $this->freelancer  = $this->getFreelancer($this->user_id);
        $this->applied_detail = $job->getAppliedDetail();
        $this->job = $job->getJob();

        
    }


   
    public function getJobAmount($jobId){
        return GuppaJob::where('id', $jobId)->first()->amount;
    }

    public function getFreelancer($freelancer){
        $freelancer =  User::with('on_boarded')->where('id',$freelancer)->first();
      
        return [
            'id' => $freelancer->id,
            'name' => $freelancer->last_name . " " . $freelancer->first_name,
            'email' => $freelancer->email,
            // 'profile_image' => asset("storage/app/public/uploads/".$freelancer->profile_photo),
            'profile_pic' => asset("storage/app/public/uploads/".$freelancer->profile_photo),
            'skill' => $freelancer->on_boarded ? explode(',',$freelancer->on_boarded->skills)[0] : "",
            'chat_id' => $freelancer->chatId


        ];
    }
   
    public function toArray(){
        return [
            'contract_id' => $this->contract_id,
            'guppa_job_id' => $this->guppa_job_id,
            'user_id' => $this->user_id,
            'applied_job_id' => $this->applied_job_id,
            'progress' => $this->progress,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'milestone' => $this->milestone,
            'contract_rate' => $this->contract_rate,
            'freelancer' => $this->freelancer,
            'applied_detail' =>  $this->applied_detail,
            'job' => $this->job

        ];
    }
}