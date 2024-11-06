<?php

namespace App\Listeners;

use App\Events\InviteEvent;
use App\Mail\InviteMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class InviteListener
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
    public function handle(InviteEvent $event): void
    {
        $url = "https://guppa-ftend.vercel.app/dashboard/freelancer/invites";
        Mail::to($event->freelancer->email, $event->freelancer->first_name)
        ->send(new InviteMail($event->freelancer, $event->job, $url));

    }
}
