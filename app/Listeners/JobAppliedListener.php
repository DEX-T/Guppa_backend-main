<?php

namespace App\Listeners;

use App\Events\JobAppliedEvent;
use App\Mail\JobApplicationNotificationClientMail;
use App\Mail\JobApplicationNotificationMail;
use App\Models\AppliedJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class JobAppliedListener
{
    /**
     * Handle the event.
     *
     * @param  JobAppliedEvent  $event
     * @return void
     */
    public function handle(JobAppliedEvent $event)
    {
        $job = $event->job;
        $freelancer = $event->freelancer;
        $client = $event->client;

        $appliedJob = AppliedJob::with('milestones')->where('id', $event->applied_id)->first();
       
        // Send email to freelancer

        Mail::to($freelancer->email, $freelancer->last_name)->send(new JobApplicationNotificationMail($job, $freelancer));

        Mail::to($client->email, $client->last_name)->send(new JobApplicationNotificationClientMail($job, $client, $freelancer, $appliedJob));


    }
}
