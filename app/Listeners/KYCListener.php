<?php

namespace App\Listeners;

use App\Events\KYCEvent;
use App\Mail\KYCNotificationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class KYCListener
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
    public function handle(KYCEvent $event): void
    {
        Mail::to($event->client->email, $event->client->first_name)->send(new KYCNotificationMail($event->client, $event->verification));
    }
}
