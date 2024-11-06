<?php

namespace App\Mail;

use App\Models\Guppa;
use App\Models\GuppaJob;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobApplicationNotificationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $job;
    public $freelancer;
    /**
     * Create a new message instance.
     */
    public function __construct(GuppaJob $job, User $freelancer)
    {
        $this->job = $job;
        $this->freelancer = $freelancer;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Job Application Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.job-notification-freelancer',
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
