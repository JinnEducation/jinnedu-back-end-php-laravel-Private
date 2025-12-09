<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\Chat;
use App\Models\ChatContact;
use App\Models\ForbiddenWords;
use Bouncer;
use Carbon\Carbon;
use Mail;

use DateTime;
use Kreait\Firebase\Contract\Database;

class ChatController extends Controller
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function contacts(Request $request, $id = 0)
    {
        $user = Auth::user();

        //get contacts for specific user
        if ($request->user_id) {
            $user = User::find($request->user_id);
            if (!$user) {
                return response([
                    'success' => false,
                    'message' => 'user-not-found'
                ], 200);
            }
        }

        $contacts = User::select('users.name', 'users.id', 'users.type', 'users.avatar', 'users.online', 'users.last_online_date', 'contacts.user_id', 'contacts.contact_id', 'contacts.last_msg', 'contacts.status', 'contacts.updated_at')->leftJoin(\DB::raw('(select * from chat_contacts where user_id=' . $user->id . ') contacts'), 'contacts.contact_id', 'users.id');

        if (intval($id) > 0) {
            $contacts = $contacts->where('users.id', $id)->first();
            $this->processContact($contacts, $user);
        } else {
            $contacts->where('contacts.user_id', $user->id);

            if (!empty($request->q)) {
                $contacts->whereRaw(filterTextDB('users.name') . ' like ?', ['%' . filterText($request->q) . '%']);
                $contacts->distinct();
            }
            //$items->orderBy('updated_at','DESC');
            $contacts = paginate($contacts, setDataTablePerPageLimit($request->limit));

            foreach ($contacts as $contact) {
                $this->processContact($contact, $user);
            }
        }


        return response([
            'success' => true,
            'message' => 'contacts-listed-successfully',
            'result' => $contacts
        ], 200);
    }

    private function processContact($contact, $user)
    {
        if ($contact) {

            $lastOnlineDate = new Carbon($contact->last_online_date);
            // $contact->since_start = $lastOnlineDate->diffForHumans();
            // $contact->since_start = $lastOnlineDate->diff(new DateTime());

            $interval = $lastOnlineDate->diff(new DateTime());
            $intervalArray = (array) $interval;
            unset($intervalArray['from_string']);

            $contact->since_start = (object) $intervalArray;
            $contact->unseen = $contact->chats->where('to_user', $user->id)->where('seen', 0)->count();
        }
    }


    // public function contacts(Request $request, $id = 0)
    // {
    //     $user = Auth::user();

    //     $items = User::select('users.name', 'users.id', 'users.type', 'users.avatar', 'users.online', 'users.last_online_date', 'contacts.last_msg', 'contacts.status', 'contacts.updated_at')
    //     ->leftJoin(\DB::raw('(select * from chat_contacts where user_id=' . $user->id . ') contacts'), 'contacts.contact_id', 'users.id');


    //     if(intval($id) > 0) {
    //         $items = $items->where('id', $id)->first();

    //     } else {
    //         $items->where('contacts.user_id', $user->id);
    //         $limit = setDataTablePerPageLimit($request->limit);

    //         if(!empty($request->q)) {
    //             $items->whereRaw(filterTextDB('name') . ' like ?', ['%' . filterText($request->q) . '%']);
    //             $items->distinct();
    //         }
    //         $items->orderBy('updated_at', 'DESC');
    //         $items = $items->paginate($limit);

    //         foreach($items as $item) {
    //             $last_online_date = new DateTime($item->last_online_date);
    //             $since_start = $last_online_date->diff(new DateTime()); //date('Y-m-d H:i:s')
    //             $item->since_start = $since_start;
    //             $item->unseen = Chat::where('from_user', $item->id)->where('to_user', $user->id)->where('seen', 0)->count();
    //         }

    //     }


    //     return response([
    //             'success' => true,
    //             'message' => 'item-listed-successfully',
    //             'result' => $items
    //     ], 200);
    // }



    // public function messagesList(Request $request, $id)
    // {

    //     $user = Auth::user();

    //     $contact = User::select('users.name', 'users.id', 'users.type', 'users.avatar', 'users.online', 'users.last_online_date', 'contacts.last_msg', 'contacts.status', 'contacts.updated_at')
    //     ->leftJoin(\DB::raw('(select * from chat_contacts where user_id=' . $user->id . ') contacts'), 'contacts.contact_id', 'users.id')->where('users.id', $id)->first();

    //     $limit = setDataTablePerPageLimit($request->limit);

    //     Chat::query()->whereRaw("(from_user=? and to_user=?)", [$id, $user->id])->update(['seen' => 1,'seen_date' => date('Y-m-d H:i:s')]);

    //     // $items = Chat::query()->whereRaw("((from_user=? and to_user=?) or (from_user=? and to_user=?))", [$user->id, $id, $id, $user->id]);
    //     $items = Chat::where(function ($query) use ($user, $id) {
    //         $query->where('from_user', $user->id)
    //               ->where('to_user', $id);
    //     })
    //     ->orWhere(function ($query) use ($user, $id) {
    //         $query->where('from_user', $id)
    //             ->where('to_user', $user->id);
    //     })
    //     ->get();
    //     if(!empty($request->last_id)) {
    //         $items->where('id', '<', $request->last_id);
    //     }
    //     if(!empty($request->first_id)) {
    //         $items->where('id', '>', $request->first_id);
    //         $limit = 1000;
    //     }
    //     $items->orderBy('id', 'desc');

    //     //dd($items->toSql());
    //     $items = $items->paginate($limit);
    //     foreach($items as $item) {
    //         $item->fromUser;
    //         $item->toUser;

    //         $start_date = new DateTime($item->created_at);
    //         $since_start = $start_date->diff(new DateTime()); //date('Y-m-d H:i:s')
    //         /*echo $since_start->days.' days total<br>';
    //         echo $since_start->y.' years<br>';
    //         echo $since_start->m.' months<br>';
    //         echo $since_start->d.' days<br>';
    //         echo $since_start->h.' hours<br>';
    //         echo $since_start->i.' minutes<br>';
    //         echo $since_start->s.' seconds<br>';*/

    //         $item->since_start = $since_start;

    //         if($item->from_user == $user->id) {
    //             $item->direction = 'start';
    //         } else {
    //             $item->direction = 'end';
    //         }
    //     }

    //     return response([
    //            'success' => true,
    //            'message' => 'item-listed-successfully',
    //            'result' => $items,
    //            'contact' => $contact
    //     ], 200);
    // }

    public function messagesList(Request $request, $id)
    {
        $user = Auth::user();

        //get messagesList for specific user
        if ($request->user_id) {
            $user = User::find($request->user_id);
            if (!$user) {
                return response([
                    'success' => false,
                    'message' => 'user-not-found'
                ], 200);
            }
        }

        // Retrieve contact using relationships
        $contact = User::with('chatContacts')
            ->where('id', $id)
            ->first();

        $limit = setDataTablePerPageLimit($request->limit);

        // Mark messages as seen
        Chat::where('from_user', $id)
            ->where('to_user', $user->id)
            ->update(['seen' => 1, 'seen_date' => now()]);

        // Retrieve messages using relationships
        $items = Chat::with(['fromUser', 'toUser'])
            ->where(function ($query) use ($user, $id) {
                $query->where('from_user', $user->id)
                    ->where('to_user', $id);
            })
            ->orWhere(function ($query) use ($user, $id) {
                $query->where('from_user', $id)
                    ->where('to_user', $user->id);
            })
            ->when(!empty($request->last_id), function ($query) use ($request) {
                $query->where('id', '<', $request->last_id);
            })
            ->when(!empty($request->first_id), function ($query) use ($request) {
                $query->where('id', '>', $request->first_id);
            })
            ->orderBy('id', 'desc')
            ->paginate($limit);




        // Process additional data for each message
        foreach ($items as $item) {
            $start_date = $item->created_at;
            $since_start = $start_date->diffForHumans();
            $item->since_start = $since_start;
            $item->direction = ($item->from_user == $user->id) ? 'end' : 'start';
        }

        return response([
            'success' => true,
            'message' => 'items listed successfully',
            'result' => $items,
            'contact' => $contact,
        ], 200);
    }


    public function listByUser(Request $request, $user_id)
    {
        $user = Auth::user();

        $limit = setDataTablePerPageLimit($request->limit);

        $items = Chat::whereRaw('((from_user=? and to_user=?) or (from_user=? and to_user=?))', [$user->id, $user_id, $user_id, $user->id])->orderby('id', 'desc')->paginate($limit);

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items
        ], 200);
    }

    public function sendMessage(Request $request)
    {
        $forbidden_words = ForbiddenWords::all();
        $data = $request->only(['to_user', 'message']);

        if (preg_match('/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/si', $data['message'])) {
            return response([
                'success' => false,
                'message' => 'contain-email-address',
                'msg-code' => '999'
            ], 200);
        }

        $phoneNumber = preg_replace('/[^0-9]/', '', $data['message']);

        if ((strlen($phoneNumber) >= 7 && strlen($phoneNumber) <= 14) || preg_match('/^(\+|0)\d*/', $data['message'])) {
            return response([
                'success' => false,
                'message' => 'contain-phone-number',
                'msg-code' => '888'
            ], 200);
        }

        if (preg_match('/\b(?:https?:\/\/(?:www\.)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b|(?:www\.[^\s\/$.?#]+\.[^\s]*)|(?:[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b))\b(?:[-a-zA-Z0-9()@:%_\\+.~#?&\\/=]*)/si', $data['message'])) {
            return response([
                'success' => false,
                'message' => 'contain-url',
                'msg-code' => '777'
            ], 200);
        }

        // Check for forbidden words
        foreach ($forbidden_words as $word) {
            if (stripos($data['message'], $word->word) !== false) {
                return response([
                    'success' => false,
                    'message' => 'contain-forbidden-word',
                    'forbidden_word' => $word->word,
                    'msg-code' => '666'
                ], 200);
            }
        }

        $user = Auth::user();
        $data['from_user'] = $user->id;
        $data['ipaddress'] = $request->ip();

        $item = Chat::create($data);

        $item->fromUser;
        $item->toUser;

        if ($item->from_user == $user->id) {
            $item->direction = 'start';
        } else {
            $item->direction = 'end';
        }

        $this->addToContacts($item);

        $room = "chat_";
        $users = [$user->id, $data['to_user']];
        sort($users);
        $room .= implode('_', $users);

        $getMessage = Chat::find($item->id);
        $message_data = [
            'id' => $getMessage->id,
            'message' => $getMessage->message,
            'from_user' =>   $getMessage->fromUser,
            'to_user' =>   $getMessage->toUser,
            "status" => $getMessage->status,
            "fav" => $getMessage->fav,
            "seen" => $getMessage->seen,
            "seen_date" => $getMessage->seen_date,
            "ipaddress" => $getMessage->ipaddress,
            "created_at" => $getMessage->created_at,
            "updated_at" => $getMessage->updated_at,
            "deleted_at" => $getMessage->deleted_at,
            "since_start" => $getMessage->created_at->diffForHumans(),
            "direction" => $getMessage->direction
        ];

        $data = [
            'chat/' . $room    => $message_data
        ];

        $this->database->getReference()->update($data);

        $receiver_user = User::find($request->to_user);
        $info = [
            'type' => 'chat',
            "senderUserId" => $user->id,
            "senderUserName" => $user->name,
            "senderUserImage" => $user->avatar
        ];

        if ($receiver_user && $receiver_user->fcm) {
            sendFCMNotification(
                $user->name,
                $getMessage->message,
                $receiver_user->fcm,
                $info,
                $receiver_user->id
            );
        }


        return response([
            'success' => true,
            'message' => 'item-added-successfully',
            'result' => $item
        ], 200);
    }

    public function onlineStatus()
    {
        $user = Auth::user();
        //whereRaw('(user_id=? and contact_id=?) or (user_id=? and contact_id=?)',[$user->id,$contact_id,$contact_id,$user->id])
        User::where('id', $user->id)->update(['online' => 1, 'last_online_date' => date('Y-m-d H:i:s')]);
        User::whereRaw('online=1 and TIMESTAMPDIFF(MINUTE,last_online_date,?)>=5', [date('Y-m-d H:i:s')])->update(['online' => 0]);
        ChatContact::whereRaw('id in (select id from users where online=0) and status=1', [])->update(['status' => 0]);
        //$items = User::select(\DB::raw('TIMESTAMPDIFF(MINUTE,?,last_online_date) diff'))->whereRaw('online=1 and TIMESTAMPDIFF(MINUTE,?,last_online_date)>=5 or 1=1',[date('Y-m-d H:i:s'),date('Y-m-d H:i:s')])->get();

        return response([
            'success' => true,
            'message' => 'item-added-successfully',
            'result' => 'online',
            'date' => date('Y-m-d H:i:s')
        ], 200);
    }

    public function typingStatus($contact_id, $status)
    {
        $user = Auth::user();
        //whereRaw('(user_id=? and contact_id=?) or (user_id=? and contact_id=?)',[$user->id,$contact_id,$contact_id,$user->id])
        ChatContact::where('user_id', $contact_id)->where('contact_id', $user->id)->update(['status' => $status]);

        return response([
            'success' => true,
            'message' => 'item-added-successfully',
            'result' => 'typing'
        ], 200);
    }

    public function addToContacts($message)
    {
        $user = Auth::user();
        $item = ChatContact::where('user_id', $user->id)->where('contact_id', $message->to_user)->first();
        if (!$item) {
            $item = new ChatContact();
            $item->user_id = $user->id;
            $item->contact_id = $message->to_user;
            $item->last_msg = $message->message;
            $item->last_msg_date = date('Y-m-d H:i:s');
            $item->save();
        } else {
            ChatContact::where('user_id', $user->id)->where('contact_id', $message->to_user)->update(['last_msg' => $message->message, 'last_msg_date' => date('Y-m-d H:i:s')]);
        }

        $item = ChatContact::where('user_id', $message->to_user)->where('contact_id', $user->id)->first();
        if (!$item) {
            $item = new ChatContact();
            $item->user_id = $message->to_user;
            $item->contact_id = $user->id;
            $item->last_msg = $message->message;
            $item->last_msg_date = date('Y-m-d H:i:s');
            $item->save();
        }
        ChatContact::where('user_id', $message->to_user)->where('contact_id', $user->id)->update(['status' => 0, 'last_msg' => $message->message, 'last_msg_date' => date('Y-m-d H:i:s')]);
    }

    public function show($id)
    {
        $user = Auth::user();
        $item = Chat::find($id);
        if (!$item) {
            return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
            ], 200);
        }

        if ($item->from_user != $user->id && $item->to_user != $user->id) {
            return response([
                'success' => false,
                'message' => 'item-dose-not-allow-show',
                'msg-code' => '111'
            ], 200);
        }

        if ($item->to_user == $user->id) {
            $item->seen = 1;
            $item->seen_date = date('Y-m-d H:i:s');
            $item->save();
        }
        return response([
            'success' => true,
            'message' => 'item-showen-successfully',
            'result' => $item
        ], 200);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $item = Chat::find($id);

        if (!$item) {
            return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
            ], 200);
        }

        if ($item->from_user != $user->id) {
            return response([
                'success' => false,
                'message' => 'item-dose-not-delete',
                'msg-code' => '111'
            ], 200);
        }

        $item->delete();

        return response([
            'success' => true,
            'message' => 'item-deleted-successfully'
        ], 200);
    }
}
