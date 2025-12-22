<?php

namespace App\Events\Chat;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TypingUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $fromUserId,
        public int $toUserId,
        public bool $status,
        public string $room
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat.' . $this->room);
    }

    public function broadcastAs(): string
    {
        return 'typing.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'from_user' => $this->fromUserId,
            'to_user' => $this->toUserId,
            'status' => $this->status ? 1 : 0,
        ];
    }
}
