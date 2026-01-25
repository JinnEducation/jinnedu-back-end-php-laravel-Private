<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Chat\ChatContactService;
use Illuminate\Http\Request;

class ChatContactsController extends Controller
{
    public function __construct(protected ChatContactService $contactService)
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $userId = (int) $request->user()->id;
        $q = $request->query('q');
        $perPage = (int) ($request->query('per_page', 20));

        $data = $this->contactService->listForUser($userId, $q, $perPage);

        return response()->json($data);
    }

    public function show(Request $request, int $id)
    {
        $userId = (int) $request->user()->id;

        $contact = $this->contactService->getContact($userId, $id);

        if (! $contact) {
            // لو مش موجود، ننشئ pair ونرجع صف فاضي (عشان يبدأ شات)
            $this->contactService->ensurePair($userId, $id);
            $contact = $this->contactService->getContact($userId, $id);
        }

        return response()->json($contact);
    }

    public function users(Request $request)
    {
        $authId = $request->user()->id;

        $q = $request->get('q');

        // المستخدمين اللي ما في بينهم وبين auth user أي chat_contact
        $users = User::query()
            ->where('id', '!=', $authId)
            ->whereNotIn('id', function ($sub) use ($authId) {
                $sub->select('contact_id')
                    ->from('chat_contacts')
                    ->where('user_id', $authId);
            })
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->select('id', 'name', 'email', 'avatar', 'type')
            ->limit(20)
            ->get();
        $users->each(function ($user) {
            $user->append('full_name');
            $user->name = $user->full_name;
        });

        return response()->json([
            'data' => $users,
        ]);
    }
}
