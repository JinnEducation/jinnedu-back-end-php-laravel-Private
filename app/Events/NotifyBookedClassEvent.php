<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Conference;

class NotifyBookedClassEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conference, $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Conference $conference, $message)
    {
        $this->conference = $conference;
        $this->message = $message;
    }

}
