<?php

namespace App\Events;

use App\Models\GuppaJob;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InviteEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
   
    public $freelancer;
    public $job;
    /**
     * Create a new event instance.
     */
    public function __construct(User $freelancer, GuppaJob $job)
    {
        $this->freelancer = $freelancer;
        $this->job = $job;
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
