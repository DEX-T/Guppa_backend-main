<?php

namespace App\Domain\Entities;

use App\Models\AppliedJob;
use App\Models\GuppaJob;
use App\Models\Milestone;
use App\Models\User;

class AppliedJobEntity
{
    private $applied_id;
    private  $guppa_job_id;
    private  $user_id;
    private  $client_id;
    private  $bid_point;
    private  $service_charge;
    private  $total_amount_payable;
    private  $project_timeline;
    private  $cover_letter_file;
    private  $cover_letter;
    private  $payment_type;
    private  $project_price;
    private  $total_milestone_price;
    private  $status;
    private $milestone;
   

    public function __construct($job){
        $this->applied_id = $job->id;
        $this->guppa_job_id = $job->guppa_job_id;
        $this->user_id = $job->user_id;
        $this->client_id = $this->getClientIdFromJob($job->guppa_job_id);
        $this->bid_point = $job->bid_point;
        $this->service_charge = $job->service_charge;
        $this->total_amount_payable = $job->total_amount_payable;
        $this->project_timeline = $job->project_timeline;
        $this->cover_letter_file = $job->cover_letter_file;
        $this->cover_letter = $job->cover_letter;
        $this->payment_type = $job->payment_type;
        $this->project_price = $job->project_price;
        $this->total_milestone_price = $job->total_milestone_price;
        $this->status = $job->status;
        $this->milestone = $this->getMilestonesDetail($job->id);
        
    }

    public function getClientIdFromJob($jobId){
        $clientId = GuppaJob::where('id', $jobId)->first();
        return $clientId->user_id;
    }
 
    public function getMilestone(){
        return $this->milestone;
    }
   
    public function getAppliedId(){
        return $this->applied_id;
    }
    
    public function getStatus(){
        return $this->status;
    }

    public function getTotalMilestonePrice(){
        return $this->total_milestone_price;
    }

    public function getProjectPrice(){
        return $this->project_price;
    }

    public function getPaymentType() {
        return $this->payment_type;
    }

    public function getCoverLetter(){
        return $this->cover_letter;
    }

    public function getCoverLetterFile(){
        return asset('storage/app/public/uploads/'.$this->cover_letter_file);

    }

    public function getProjectTimeline(){
        return $this->project_timeline;
    }

    public function getTotalAmountPayable(){
        return $this->total_amount_payable;
    }
    //get guppa job id 
    public function getJobId(){
        return $this->guppa_job_id;
    }

    //get user id
    public function getUserId(){
        return $this->user_id;
    }
    public function getClientId(){
        return $this->client_id;
    }

    public function getBidPoint(){
        return $this->bid_point;
    }

    public function getServiceCharge(){
        return $this->service_charge;
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
    
}