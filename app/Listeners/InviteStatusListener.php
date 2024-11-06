<?php

namespace App\Listeners;

use App\Events\InviteStatusEvent;
use App\Mail\InviteStatusMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class InviteStatusListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(InviteStatusEvent $event): void
    {
        Mail::to($event->client->email, $event->client->first_name)->send(new InviteStatusMail($event->invites, $event->client, $event->freelancer, $event->job));
    }
}
