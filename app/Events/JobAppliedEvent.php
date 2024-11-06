<?php

namespace App\Events;

use App\Models\AppliedJob;
use App\Models\GuppaJob;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobAppliedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $job;
    public $freelancer;
    public $client;
    public $applied_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(GuppaJob $job, User $freelancer, User $client, $applied_id)
    {
        $this->job = $job;
        $this->freelancer = $freelancer;
        $this->client = $client;
        $this->applied_id = $applied_id;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
