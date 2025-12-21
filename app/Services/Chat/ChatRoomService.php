<?php

namespace App\Services\Chat;

class ChatRoomService
{
    public static function make(int $a, int $b): string
    {
        $users = [$a, $b];
        sort($users);
        return implode('_', $users);
    }

    public static function userInRoom(int $userId, string $room): bool
    {
        $parts = explode('_', $room);
        $parts = array_map('intval', $parts);
        return in_array($userId, $parts, true);
    }
}
