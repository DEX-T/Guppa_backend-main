<?php

 namespace App\Domain\DTOs\Response\Reports;

use App\Domain\Entities\ContractEntity;
use App\Models\AppliedJob;
use App\Models\GuppaJob;
use App\Models\User;

class ContractsReportResponseDto
{
    public  $progress;
    public  $total_earnings;
    public  $status;
    public  $created_at;
    public $milestone;
    public $client;
    public $freelancer;
    public $contract_rate;
    public $payable;
    public function __construct(ContractEntity $job){
        $this->progress = $job->getProgress();
        $this->total_earnings = $job->getTotalEarnings();
        $this->status = $job->getStatus();
        $this->created_at = $job->getCreatedAt();
        $this->milestone = $job->getMilestone();
        $this->client = $this->getClientDetail($job->getClientId());
        $this->freelancer = $this->getFreelancerDetails($job->getUserId());
        $this->contract_rate = $this->getJobAmount($job->getJobId());
        $this->payable = $this->getPayableAmount($job->getAppliedJobId());

    }


    public function getClientDetail($clientId): array
    {
        $client = User::where('id', $clientId)->first();
        return [
            'id' => $client->id,
            'name' => $client->last_name . " " . $client->first_name,
            'profile_photo' => asset('storage/app/public/uploads/' . $client->profile_photo),
            'email' => $client->email,
            
        ];
    }

    public function getFreelancerDetails($freelancerId): array
    {
        $freelancer = User::where('id', $freelancerId)->first();
        return [
            'id' => $freelancer->id,
            'name' => $freelancer->last_name . " " . $freelancer->first_name,
            'profile_photo' => asset('storage/app/public/uploads/' . $freelancer->profile_photo),
            'email' => $freelancer->email
        ];
    }

    public function getJobAmount($jobId){
        return GuppaJob::where('id', $jobId)->first()->amount;
    }

    public function getPayableAmount($applied_job_id){
        return AppliedJob::where('id', $applied_job_id)->first()->total_amount_payable;
    }

    public function toArray(): array
    {
        return [
            'progress' => $this->progress,
            'total_earnings' => $this->total_earnings,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'milestone' => $this->milestone,
            'client' => $this->client,
            'freelancer' => $this->freelancer,
            'contract_rate' => $this->contract_rate,
            'payable' => $this->payable,
        ];
    }
}
