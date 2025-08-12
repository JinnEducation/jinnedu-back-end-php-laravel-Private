<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\Invite;


use Bouncer;
use Mail;

class InviteController extends Controller
{
    
    public function addInvite(Request $request){
        $user = \Auth::user();
        $email = $request->get('email');
        if(!$email){
             return response([
                        'success' => false,
                        'message' => 'email-required',
                        'result' => ''
                ] , 400);
        }
        
        $invite = new Invite();
        $invite->user_id = $user->id;
        $invite->email = $email;
        $saved = $invite->save();
        if(!$saved){
            return response([
                        'success' => false,
                        'message' => 'email-required',
                        'result' => ''
                ] , 400);
        }
        
        
        $data = array('user'=>$user);
        \Mail::send('emails.invite', $data, function($message) use ($user,$email){
            $message->to($email,$email)->subject('Invite Friend');
            $message->from($user->email,$user->name);
        });
        
        return response([
                        'success' => true,
                        'message' => 'invited-successfully',
                        'result' => ''
                ] , 200);
        
    }
    
}