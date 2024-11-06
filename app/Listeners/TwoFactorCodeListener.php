<?php

namespace App\Listeners;

use App\Events\TwoFactorCodeEvent;
use App\Mail\TwoFactorCodeEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class TwoFactorCodeListener
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
    public function handle(TwoFactorCodeEvent $event): void
    {
        Mail::to($event->user->email)->send(new TwoFactorCodeEmail($event->user, $event->code));
    }
}
