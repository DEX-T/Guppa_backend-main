<?php

namespace App\Mail;

use App\Models\GuppaJob;
use App\Models\User;
use App\Models\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviteStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invites;
    public $client;
    public $freelancer;
    public $job;
    /**
     * Create a new event instance.
     */
    public function __construct(Invite $invites, User $client, User $freelancer, GuppaJob $job)
    {
        $this->invites = $invites;
        $this->client = $client;
        $this->freelancer = $freelancer;
        $this->job = $job;
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitation Status',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invites-status',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
