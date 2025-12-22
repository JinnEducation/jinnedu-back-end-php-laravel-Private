<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;

class ChatPolicy
{
    public function viewConversation(User $user, int $contactId): bool
    {
        return $user->id !== $contactId;
    }

    public function viewMessage(User $user, Chat $chat): bool
    {
        return $chat->from_user === $user->id || $chat->to_user === $user->id;
    }
}
