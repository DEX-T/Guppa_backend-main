<?php

 namespace App\Domain\DTOs\Response\Jobs;

use App\Models\Tag;
use App\Models\User;
use App\Models\JobTag;
use App\Models\GuppaJob;
use App\Models\Milestone;
use App\Domain\Entities\AppliedJobEntity;

class AppliedJobResponseDto
{
    public $applied_id;
    public  $guppa_job_id;
    public  $user_id;
    public  $bid_point;
    public  $service_charge;
    public  $total_amount_payable;
    public  $project_timeline;
    public  $cover_letter_file;
    public  $cover_letter;
    public  $payment_type;
    public  $project_price;
    public  $total_milestone_price;
    public  $status;
    public $user;
    public $milestone;
    public $job;

    public function __construct(AppliedJobEntity $job){
        $this->applied_id = $job->getAppliedId();
        $this->guppa_job_id = $job->getJobId();
        $this->user_id = $job->getUserId();
        $this->bid_point = $job->getBidPoint();
        $this->service_charge = $job->getServiceCharge();
        $this->total_amount_payable = $job->getTotalAmountPayable();
        $this->project_timeline = $job->getProjectTimeline();
        $this->cover_letter_file = $job->getCoverLetterFile();
        $this->cover_letter = $job->getCoverLetter();
        $this->payment_type = $job->getPaymentType();
        $this->project_price = $job->getProjectPrice();
        $this->total_milestone_price = $job->getTotalMilestonePrice();
        $this->status = $job->getStatus();
        $this->user = $this->getFreelancerDetail($job->getUserId());
        $this->milestone = $job->getMilestone();
        $this->job =$this->getJobDetail($job->getJobId());
    }


    public function getFreelancerDetail($freelancer){
        $freelancer =  User::with('on_boarded')->where('id',$freelancer)->first();
        
        return [
            'id' => $freelancer->id,
            'name' => $freelancer->last_name . " " . $freelancer->first_name,
            'email' => $freelancer->email,
            'profile_pic' => asset('storage/app/public/uploads/'.$freelancer->profile_photo),
            'skill' => $freelancer->on_boarded ? explode(',',$freelancer->on_boarded->skills)[0] : "",
            'chat_id' => $freelancer->chatId

        ];
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
            'tags' =>  $this->grabTags($job->id),
           'amount' => $job->amount,
           'time' => $job->time,
           'required_skills' => $job->required_skills,
           'category' => $job->category

        ];
    }
    

    public function toArray(){
        return [
            'applied_id' => $this->applied_id,
            'guppa_job_id' => $this->guppa_job_id,
            'user_id' => $this->user_id,
            'bid_point' => $this->bid_point,
            'service_charge' => $this->service_charge,
            'total_amount_payable' => $this->total_amount_payable,
            'project_timeline' => $this->project_timeline,
            'cover_letter_file' => asset('storage/app/public/uploads/'.$this->cover_letter_file),
            'cover_letter' => $this->cover_letter,
            'payment_type' => $this->payment_type,
            'project_price' => $this->project_price,
            'total_milestone_price' => $this->total_milestone_price,
            'status' => $this->status,
            'user' => $this->user,
            'milestone' => $this->milestone,
            'job' => $this->job
        ];
    }
}