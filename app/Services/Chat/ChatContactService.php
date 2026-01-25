<?php

namespace App\Services\Chat;

use App\Models\Chat;
use App\Models\ChatContact;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ChatContactService
{
    public function listForUser(int $userId, ?string $q = null, int $perPage = 20): LengthAwarePaginator
    {
        $contacts = ChatContact::query()
            ->forUser($userId)
            ->with(['contact:id,name,email','user']) // عدّلي حسب أعمدة User عندك
            ->when($q, function ($query) use ($q) {
                $query->whereHas('contact', function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('last_msg_date')
            ->paginate($perPage);

        // أضف unread_count لكل contact
        $contactIds = collect($contacts->items())->pluck('contact_id')->all();

        $unseen = Chat::query()
            ->select('from_user', DB::raw('count(*) as c'))
            ->where('to_user', $userId)
            ->where('seen', 0)
            ->whereIn('from_user', $contactIds)
            ->groupBy('from_user')
            ->pluck('c', 'from_user');

        foreach ($contacts as $item) {
            $item->unread_count = (int)($unseen[$item->contact_id] ?? 0);
            $contact = $item->contact;   // ← خذ نسخة واضحة من الموديل

            if ($contact) {
                $contact->name = $contact->name ?? $contact->full_name;
                $contact->avatar = $contact->avatar ?: asset('assets/images/avatar.png');
                $contact->type = $contact->type ?? 0;
                $contact->online = $contact->online ?? 0;
                $contact->last_online_date = $contact->last_online_date ?? null;
            }
            // $item->contact->name = $item->contact?->name ?? $item->contact?->full_name ;
            // $item->contact->avatar = $item->contact->avatar ?? asset('assets/images/avatar.png');
            // $item->contact->type = $item->contact->type ?? 0;
            // $item->contact->online = $item->contact->online ?? 0;
            // $item->contact->last_online_date = $item->contact->last_online_date ?? null;

        }

        return $contacts;
    }

    public function getContact(int $userId, int $contactId): ?ChatContact
    {
        $chat = ChatContact::query()
            ->forUser($userId)
            ->where('contact_id', $contactId)
            ->with(['contact:id,name,email'])
            ->first();

    if ($chat?->contact) {
        $chat->contact->append('full_name');
        $chat->contact->name = $chat->contact->full_name;
    }

        return $chat;
    }

    public function ensurePair(int $userId, int $contactId): void
    {
        // user -> contact
        ChatContact::query()->firstOrCreate(
            ['user_id' => $userId, 'contact_id' => $contactId],
            ['last_msg' => null, 'last_msg_date' => null, 'status' => 0]
        );

        // contact -> user
        ChatContact::query()->firstOrCreate(
            ['user_id' => $contactId, 'contact_id' => $userId],
            ['last_msg' => null, 'last_msg_date' => null, 'status' => 0]
        );
    }

    public function updateLastMessage(int $userId, int $contactId, string $msg, $dateTime): void
    {
        ChatContact::query()
            ->where('user_id', $userId)
            ->where('contact_id', $contactId)
            ->update([
                'last_msg' => $msg,
                'last_msg_date' => $dateTime,
            ]);
    }

    public function setTyping(int $userId, int $contactId, bool $status): void
    {
        ChatContact::query()
            ->where('user_id', $userId)
            ->where('contact_id', $contactId)
            ->update(['status' => $status ? 1 : 0]);
    }
}
