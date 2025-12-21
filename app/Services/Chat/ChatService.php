<?php

namespace App\Services\Chat;

use App\Events\Chat\MessageSent;
use App\Events\Chat\MessagesSeen;
use App\Events\Chat\TypingUpdated;
use App\Models\Chat;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatService
{
    public function __construct(
        protected ChatContactService $contactService
    ) {}

    public function listMessages(int $authId, int $contactId, int $perPage = 20): LengthAwarePaginator
    {
        // تأكد contact pairs موجودة
        $this->contactService->ensurePair($authId, $contactId);

        $messages = Chat::query()
            ->betweenUsers($authId, $contactId)
            ->orderByDesc('id')
            ->paginate($perPage);

        // mark messages as seen (الرسائل القادمة للمستخدم)
        $this->markAsSeen($authId, $contactId);

        return $messages;
    }

    public function sendMessage(int $fromUserId, int $toUserId, string $message, ?string $ip = null): Chat
    {
        $message = trim($message);
        if ($message === '') {
            throw new \InvalidArgumentException('Message is empty');
        }

        // هنا مكان أي rules عندكم (منع email/phone/url/words...)
        $this->validateMessageContent($message);

        return DB::transaction(function () use ($fromUserId, $toUserId, $message, $ip) {
            $this->contactService->ensurePair($fromUserId, $toUserId);

            $chat = Chat::create([
                'from_user' => $fromUserId,
                'to_user' => $toUserId,
                'message' => $message,
                'status' => 0,
                'fav' => 0,
                'seen' => 0,
                'seen_date' => null,
                'ipaddress' => $ip,
            ]);

            // تحديث last message للطرفين
            $this->contactService->updateLastMessage($fromUserId, $toUserId, $message, $chat->created_at);
            $this->contactService->updateLastMessage($toUserId, $fromUserId, $message, $chat->created_at);

            // بث real-time للطرفين
            $room = ChatRoomService::make($fromUserId, $toUserId);
            broadcast(new MessageSent($chat, $room))->toOthers();

            return $chat;
        });
    }

    public function markAsSeen(int $authId, int $contactId): int
    {
        $now = now();

        $count = Chat::query()
            ->where('from_user', $contactId)
            ->where('to_user', $authId)
            ->where('seen', 0)
            ->update([
                'seen' => 1,
                'seen_date' => $now,
            ]);

        if ($count > 0) {
            $room = ChatRoomService::make($authId, $contactId);
            broadcast(new MessagesSeen($authId, $contactId, $now, $room))->toOthers();
        }

        return $count;
    }

    public function setTyping(int $fromUserId, int $toUserId, bool $status): void
    {
        $this->contactService->ensurePair($fromUserId, $toUserId);

        // نخزن typing status على contact row للطرف الآخر (حتى يظهر له)
        $this->contactService->setTyping($toUserId, $fromUserId, $status);

        $room = ChatRoomService::make($fromUserId, $toUserId);
        broadcast(new TypingUpdated($fromUserId, $toUserId, $status, $room))->toOthers();
    }

    public function toggleFavorite(int $authId, int $messageId): Chat
    {
        $chat = Chat::query()->findOrFail($messageId);

        // حماية: فقط طرفي المحادثة
        if ($chat->from_user !== $authId && $chat->to_user !== $authId) {
            abort(403, 'Not allowed');
        }

        $chat->fav = !$chat->fav;
        $chat->save();

        return $chat;
    }

    public function deleteMessage(int $authId, int $messageId): void
    {
        $chat = Chat::query()->findOrFail($messageId);

        if ($chat->from_user !== $authId && $chat->to_user !== $authId) {
            abort(403, 'Not allowed');
        }

        $chat->delete();
    }

    protected function validateMessageContent(string $message): void
    {
        // مثال بسيط — عدّلي حسب قواعد مشروعكم
        // منع روابط
        if (preg_match('/https?:\/\/\S+/i', $message)) {
            abort(422, 'Links are not allowed in chat messages.');
        }

        // منع إيميلات
        if (preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $message)) {
            abort(422, 'Emails are not allowed in chat messages.');
        }

        // منع أرقام طويلة (phone)
        if (preg_match('/\b\d{8,}\b/', $message)) {
            abort(422, 'Phone numbers are not allowed in chat messages.');
        }
    }
}
