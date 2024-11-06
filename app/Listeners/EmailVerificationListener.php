<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use App\Events\EmailVerificationEvent;
use App\Mail\SendVerificationCodeMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerificationListener
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
    public function handle(EmailVerificationEvent $event): void
    {
        Mail::to($event->user->email)->send(new SendVerificationCodeMail($event->code, $event->user));
    }
}
