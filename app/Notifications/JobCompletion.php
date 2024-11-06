<?php

namespace App\Notifications;

use App\Models\GuppaJob;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobCompletion extends Notification implements ShouldQueue
{
    use Queueable;
    public $job;
    public $freelancer;
    /**
     * Create a new notification instance.
     */
    public function __construct(GuppaJob $job, User $freelancer)
    {
        $this->job = $job;
        $this->freelancer = $freelancer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */

    public function toMail(object $notifiable): MailMessage
    {
        $url = url('https://guppa-ftend.vercel.app/client/dashboard/contracts/overview/'.$this->job->id);

        return (new MailMessage)
            ->greeting('Hello!')
            ->line($this->freelancer->first_name . '  have completed job: ' . $this->job->title)
            ->lineIf($this->job->status == "Awaiting Review", "Status: {$this->job->status}")
            ->action('Job View', $url)
            ->line('Please go to your dashboard to review!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'Job_Title' => $this->job->title,
            'Job_Description' => $this->job->description,
            'Freelancer' => [
                'name' => $this->freelancer->first_name . " " . $this->freelancer->last_name,
                'email' => $this->freelancer->email,
                'profile_photo' => asset('storage/app/public/uploads/'. $this->freelancer->profile_photo)
            ]

        ];
    }
}
