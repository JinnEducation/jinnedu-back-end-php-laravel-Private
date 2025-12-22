<?php

namespace App\Events\Chat;

use App\Models\Chat;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Chat $chat,
        public string $room
    ) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat.' . $this->room);
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->chat->id,
            'from_user' => $this->chat->from_user,
            'to_user' => $this->chat->to_user,
            'message' => $this->chat->message,
            'fav' => (bool)$this->chat->fav,
            'seen' => (bool)$this->chat->seen,
            'seen_date' => optional($this->chat->seen_date)->toDateTimeString(),
            'created_at' => optional($this->chat->created_at)->toDateTimeString(),
        ];
    }
}
