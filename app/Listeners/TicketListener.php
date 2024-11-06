<?php

namespace App\Listeners;

use App\Events\TicketEvent;
use App\Mail\RespondTicketMail;
use App\Mail\TicketClosedMail;
use App\Mail\TicketResolvedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class TicketListener
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
    public function handle(TicketEvent $event): void
    {
        if($event->ticket->status == "active"){
            Mail::to($event->user->email, $event->user->first_name)->send(new RespondTicketMail($event->user, $event->ticket));
        }else if($event->ticket->status == "resolved"){
            Mail::to($event->user->email, $event->user->first_name)->send(new TicketResolvedMail($event->user, $event->ticket));
        }else{
            Mail::to($event->user->email, $event->user->first_name)->send(new TicketClosedMail($event->user, $event->ticket));
        }
        
    }
}
