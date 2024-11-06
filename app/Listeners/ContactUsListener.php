<?php

namespace App\Listeners;

use App\Events\ContactUsEvent;
use App\Mail\ContactUsMail;
use App\Mail\ContactUsResponderMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ContactUsListener
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
    public function handle(ContactUsEvent $event): void
    {
        $to = config('mail.contact_responder_email');
        $toName = config('mail.contact_responder_name');
        $data = [
            'name' => $toName,
            'email' => $event->email,
            'subject' => $event->subject,
            'content' => $event->content
        ];
       
        Mail::to($to, $toName)->send(new ContactUsMail($data));
        Mail::to($event->email, $event->name)->send(new ContactUsResponderMail($event->subject, $event->name));
    }
}
