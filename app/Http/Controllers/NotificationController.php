<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Notification;
use App\Notifications\OffersNotification;
use App\Notifications\GeneralNotification;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class NotificationController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }
  
    public function index(Request $request)
    {
        $user = Auth::user();
        $limit = setDataTablePerPageLimit($request->limit);
        $items = $user->notifications();
        
        if(!empty($request->read) && $request->read=='true') $items->whereRaw('read_at is not NULL',[]);
        else if(!empty($request->read) && $request->read=='false') $items->whereRaw('read_at is NULL',[]);
        
        $items = $items->paginate($limit);
        
        return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items
        ] , 200);
    }
    
    public function sendOfferNotification() {
        $userSchema = User::first();
  
        $offerData = [
            'name' => 'BOGO',
            'body' => 'You received an offer.',
            'thanks' => 'Thank you',
            'offerText' => 'Check out the offer',
            'offerUrl' => url('/'),
            'offer_id' => 007
        ];
  
        Notification::send($userSchema, new OffersNotification($offerData));
   
        dd('Task completed!');
    }
    
    public function sendGeneralNotification() {
        $user = Auth::user();
  
        $notifyData=new GeneralNotification(1,0);
        Notification::send($user , $notifyData);
  
        dd('Task completed!');
    }
    
    public function read($id){
    	$user = Auth::user();
    	$notification = $user->notifications()->where('id',$id)->first();
        // dd($notification);
    	if ($notification){
    	    if(!$notification->read_at) $notification->markAsRead();
            $url=$notification->data['url'];
    	    return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $notification
            ] , 200);
    	} else {
    		 return response([
                'success' => false,
                'message' => 'item-not-exist'
            ] , 200);
    	}
    }
    
    public function readAll(){
        $user = Auth::user();
        $notifications = $user->notifications()->whereRaw('read_at is null',[])->get();

        // $date = date('Y/m/d');
        foreach ($notifications as $notification) {
           $notification->markAsRead();
        }
        return response([
                'success' => true,
                'message' => 'item-listed-successfully'
            ] , 200);
    }

}