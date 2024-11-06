<?php

namespace App\Listeners;

use App\Events\ResetPasswordEvent;
use App\Mail\SendResetPasswordLinkMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ResetPasswordListener
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
    public function handle(ResetPasswordEvent $event): void
    {
        $email = $event->email;
        $token = $event->token;
        $url = $event->url;
        $lastName = $event->user_name;

        $mail_data = [
            'email' => $email,
            'token' => $token,
            'url' => $url,
            'lastName' => $lastName,
        ];

        Mail::to($mail_data['email'], $mail_data['lastName'])->send(new SendResetPasswordLinkMail($mail_data));
    }
}
