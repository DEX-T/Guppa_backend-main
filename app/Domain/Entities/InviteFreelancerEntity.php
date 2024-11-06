<?php

namespace App\Domain\Entities;

use App\Models\User;
use App\Models\Invite;
use App\Models\GuppaJob;
use Illuminate\Support\Facades\Log;

class InviteFreelancerEntity
{
    public $id;
    public $freelancer_id;
    public $project_id;
    public $description;
    public $status;
    public $created_at;
    public $freelancer;
    public $job;

    public function __construct(Invite $invite){
        $this->id = $invite->id;
        $this->freelancer_id = $invite->freelancer_id;
        $this->project_id = $invite->guppa_job_id;
        $this->job = $this->getProjectDetail($invite->guppa_job_id);
        $this->description = $invite->description;
        $this->status = $invite->status;
        $this->created_at = $invite->created_at;
        $this->freelancer = $this->getFreelancer($invite->freelancer_id);
    }
   

    public function getFreelancer($freelancerId){
        $freelancer = User::find($freelancerId);
        Log::info("Freelancer in invite ", [$freelancer]);

        return [
            'id' => $freelancer->id,
            'name' => $freelancer->first_name . " " . $freelancer->last_name,
            'email' => $freelancer->email,
            'phone' => $freelancer->phone_no,
            'chat_id' => $freelancer->chatId,
            'profile_picture' => asset('storage/app/public/uploads/'.$freelancer->profile_picture),
        ];
    }

    public function getProjectDetail($guppa_job){
        Log::info("job id ", [$guppa_job]);
        $job = GuppaJob::findOrFail($guppa_job);
        Log::info("job detail ", [$job]);
        return  [
            'job_id' => $job->id,
            'title' => $job->title,
        ];
        
    }
}