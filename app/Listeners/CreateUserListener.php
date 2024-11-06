<?php

namespace App\Listeners;

use App\Events\CreateUserEvent;
use App\Mail\CreateUserMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class CreateUserListener
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
    public function handle(CreateUserEvent $event): void
    {
        Mail::to($event->user->email, $event->user->first_name)->send(new CreateUserMail($event->user, $event->url));
    }
}
