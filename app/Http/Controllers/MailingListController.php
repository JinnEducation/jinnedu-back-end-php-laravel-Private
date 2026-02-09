<?php

namespace App\Http\Controllers;

use App\Models\MailingList;
use Illuminate\Http\Request;

class MailingListController extends Controller
{
    // عرض كل الإيميلات
    public function index(Request $request)
    {

        $limit = setDataTablePerPageLimit($request->limit);
        
        $items = MailingList::latest()->paginate($limit);
        

        return $items;
    }

    // إضافة إيميل
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:mailing_lists,email',
        ]);

        MailingList::create([
            'email' => $request->email,
            'user_id' => auth()->id(),
        ]);

        return response()->json(['message' => 'Subscribed successfully']);
    }

    // حذف إيميل
    public function destroy($id)
    {
        MailingList::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
