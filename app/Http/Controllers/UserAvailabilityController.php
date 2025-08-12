<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\Tutor;
use App\Models\User;
use App\Models\UserAvailability;

use Bouncer;
use Mail;

class UserAvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $limit = setDataTablePerPageLimit($request->limit);
        
         $items = UserAvailability::where('user_id',$user->id)->paginate($limit);
         
         return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items
        ] , 200);
    }

    public function store(Request $request)
    {
        $data = $request->only(['timezone_id','day_id','status','hour_from','hour_to']);
        $user = Auth::user();
        
        $tutor = Tutor::find($user->id);
        if(!$tutor) return response([
                'success' => false,
                'message' => 'tutor-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        $data['user_id'] = $user->id;
        
        $item = UserAvailability::create($data);
        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item
        ] , 200);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        $item = UserAvailability::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        if($item->user_id!=$user->id) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        $data = $request->only(['timezone_id','day_id','status','hour_from','hour_to']);
        $data['user_id'] = $user->id;
       
        $item->update($data);
        return response([
                'success' => true,
                'message' => 'item-updated-successfully',
                'result' => $item
        ] , 200);
    }
    
    public function show($id)
    {
        $user = Auth::user();
        $item = UserAvailability::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        if($item->user_id!=$user->id) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $item
        ] , 200);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $item = UserAvailability::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        if($item->user_id!=$user->id) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        $item->delete();
        
        
        return response([
                'success' => true,
                'message' => 'item-deleted-successfully'
        ] , 200);
    }
    
}