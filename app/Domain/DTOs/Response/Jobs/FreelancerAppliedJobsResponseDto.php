<?php
namespace App\Domain\DTOs\Response\Jobs;
use App\Models\Tag;
use App\Models\User;
use App\Models\JobTag;
use App\Models\GuppaJob;
use App\Models\Milestone;
use App\Domain\Entities\AppliedJobEntity;

 
class FreelancerAppliedJobsResponseDto
{
    public $applied_id;
    public  $guppa_job_id;
    public  $user_id;
    public  $client_id;
    public  $bid_point;
    public  $total_amount_payable;
    public  $project_timeline;
    public  $cover_letter_file;
    public  $cover_letter;
    public  $payment_type;
    public  $project_price;
    public  $total_milestone_price;
    public  $status;
    public $client;
    public $milestone;
    public $job;

    public function __construct(AppliedJobEntity $job){
        $this->applied_id = $job->getAppliedId();
        $this->guppa_job_id = $job->getJobId();
        $this->user_id = $job->getUserId();
        $this->client_id = $job->getClientId();
        $this->bid_point = $job->getBidPoint();
        $this->total_amount_payable = $job->getTotalAmountPayable();
        $this->project_timeline = $job->getProjectTimeline();
        $this->cover_letter_file = $job->getCoverLetterFile();
        $this->cover_letter = $job->getCoverLetter();
        $this->payment_type = $job->getPaymentType();
        $this->project_price = $job->getProjectPrice();
        $this->total_milestone_price = $job->getTotalMilestonePrice();
        $this->status = $job->getStatus();
        $this->client = $this->getClientDetail($job->getClientId());
        $this->milestone = $job->getMilestone();
        $this->job =$this->getJobDetail($job->getJobId());
    }


    public function getClientDetail($client){
        $client =  User::where('id',$client)->first();
        return [
            'id' => $client->id,
            'name' => $client->last_name . " " . $client->first_name,
            'email' => $client->email,
            'profile_pic' => asset('storage/app/public/uploads/'.$client->profile_photo),
            'chat_id' => $client->chatId
            
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
            'project_timeline' => $this->project_timeline,
            'cover_letter_file' => asset('storage/app/public/uploads/'.$this->cover_letter_file),
            'cover_letter' => $this->cover_letter,
            'payment_type' => $this->payment_type,
            'project_price' => $this->project_price,
            'total_milestone_price' => $this->total_milestone_price,
            'status' => $this->status,
            'client' => $this->client,
            'milestone' => $this->milestone,
            'job' => $this->job,
        ];
    }
}