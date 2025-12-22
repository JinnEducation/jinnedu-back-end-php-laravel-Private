<?php

namespace App\Events\Chat;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessagesSeen implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $byUserId,
        public int $contactId,
        public $seenAt,
        public string $room
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat.' . $this->room);
    }

    public function broadcastAs(): string
    {
        return 'messages.seen';
    }

    public function broadcastWith(): array
    {
        return [
            'by_user' => $this->byUserId,
            'contact_id' => $this->contactId,
            'seen_at' => $this->seenAt?->toDateTimeString(),
        ];
    }
}
