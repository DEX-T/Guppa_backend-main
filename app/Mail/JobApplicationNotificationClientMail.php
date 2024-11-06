<?php

namespace App\Mail;

use App\Models\AppliedJob;
use App\Models\GuppaJob;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobApplicationNotificationClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public $job;
    public $client;
    public $freelancer;
    public $appliedJob;

    /**
     * Create a new message instance.
     */
    public function __construct(GuppaJob $job, User $client, User $freelancer, AppliedJob $appliedJob)
    {
        $this->job = $job;
        $this->client = $client;
        $this->freelancer = $freelancer;
        $this->appliedJob = $appliedJob;
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
            view: 'emails.job-notification-client',
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
