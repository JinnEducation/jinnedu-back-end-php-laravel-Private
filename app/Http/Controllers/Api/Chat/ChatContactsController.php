<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
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
        $userId = (int)$request->user()->id;
        $q = $request->query('q');
        $perPage = (int)($request->query('per_page', 20));

        $data = $this->contactService->listForUser($userId, $q, $perPage);

        return response()->json($data);
    }

    public function show(Request $request, int $id)
    {
        $userId = (int)$request->user()->id;

        $contact = $this->contactService->getContact($userId, $id);

        if (!$contact) {
            // لو مش موجود، ننشئ pair ونرجع صف فاضي (عشان يبدأ شات)
            $this->contactService->ensurePair($userId, $id);
            $contact = $this->contactService->getContact($userId, $id);
        }

        return response()->json($contact);
    }
}
