<?php

 namespace App\Domain\DTOs\Response\Reports;

use App\Domain\Entities\AppliedJobEntity;
use App\Models\GuppaJob;
use App\Models\User;

class AppliedJobReportResponseDto
{
    public  $bid_point;
    public  $service_charge;
    public  $total_amount_payable;
    public  $project_timeline;
    public  $payment_type;
    public  $project_price;
    public  $total_milestone_price;
    public  $status;
    public $user;
    public $milestone;
    public $job;

    public function __construct(AppliedJobEntity $job){
        $this->bid_point = $job->getBidPoint();
        $this->service_charge = $job->getServiceCharge();
        $this->total_amount_payable = $job->getTotalAmountPayable();
        $this->project_timeline = $job->getProjectTimeline();
        $this->payment_type = $job->getPaymentType();
        $this->project_price = $job->getProjectPrice();
        $this->total_milestone_price = $job->getTotalMilestonePrice();
        $this->status = $job->getStatus();
        $this->user = $this->getFreelancerDetail($job->getUserId());
        $this->milestone = $job->getMilestone();
        $this->job =$this->getJobDetail($job->getJobId());
    }


    public function getFreelancerDetail($freelancer): array
    {
        $freelancer =  User::where('id',$freelancer)->first();
        return [
            'id' => $freelancer->id,
            'name' => $freelancer->last_name . " " . $freelancer->first_name,
            'email' => $freelancer->email,
            'profile_pic' => asset('storage/app/public/uploads/'.$freelancer->profile_photo)
        ];
    }

    public function getJobDetail($job_id): array
    {
        $job =  GuppaJob::where('id',$job_id)->first();
        return [
            'id' => $job->id,
            'title' => $job->title,
            'description' => $job->description,
        ];
    }


    public function toArray(): array
    {
        return [
            'bid_point' => $this->bid_point,
            'service_charge' => $this->service_charge,
            'total_amount_payable' => $this->total_amount_payable,
            'project_timeline' => $this->project_timeline,
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
