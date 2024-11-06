<?php

namespace App\Domain\Interfaces\Job;

use Illuminate\Http\Request;

interface IGuppaJobService
{
    public function upsertJob(Request $request);
    public function getAllJobs();
    public function getAvailableJobs(Request $request);
    public function getJobBySlug(string $slug);
    public function getJobById(int $id);
    public function getMyJobs();
    public function getJobForMe();
    public function apply(Request $request);
    public function extractText(Request $request);
    public function getAppliedJobs(int $JobId);
    public function getAppliedJob(int $applied_id);
    public function approveJob(int $applied_id);
    public function rejectJob(int $applied_id);
    public function getClientAppliedJobs(int $jobId);
    public function deleteJob(int $jobId);
    public function deleteAppliedJob(int $applied_id);
    public function getContracts();
    public function getContract(int $id);
    public function getContractsForClient();
    public function getContractForClient(int $id);
    public function updateFreelancerStatus(int $contract_id);
    public function updateClientStatus(int $contract_id);
    public function updateProgress(int $contract_id, int $progress);
    public function updateMilestoneProgress(int $milestone_id, string $progress);
    public function getFreelancerAppliedJob(int $applied_id);
    public function getFreelancerAppliedJobs();
}
