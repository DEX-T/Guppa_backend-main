<?php

namespace App\Listeners;

use App\Mail\WelcomeEmail;
use App\Events\AccountCreationEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeEmail
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
    public function handle(AccountCreationEvent $event): void
    {
        Mail::to($event->user->email)->send(new WelcomeEmail($event->user, $event->code));
    }
}
