<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Label\LabelRequest;

use App\Models\User;
use App\Models\Language;
use App\Models\Translation;
use App\Models\Label;

use Bouncer;
use Mail;

use Zoom;
use \Carbon\Carbon ;

//https://github.com/MacsiDigital/laravel-zoom
//https://github.com/zoom/meetingsdk-web-sample

class ZoomController extends Controller
{
    public function index(Request $request)
    {
        //https://success.zoom.us/wc/join/87973966307
        if(!empty($request->zstart)){
             $data['url'] = 'https://us05web.zoom.us/wc/'.$request->zstart.'/start';
             return view('zoom',$data);
        }else if(!empty($request->zjoin)){
             $data['url'] = 'https://success.zoom.us/wc/join/'.$request->zjoin;
             return view('zoom',$data);
        }else{
           $user = Zoom::user()->first();
            //dd($user);
            //dd(config('zoom.auto_recording'));
             $meeting = Zoom::meeting()->make([
                  'topic' => 'New meeting123',
                  'type' => 8,
                  'start_time' => new Carbon('2020-08-12 10:00:00'), // best to use a Carbon instance here.
            ]);
            
            $meeting->recurrence()->make([
              'type' => 2,
              'repeat_interval' => 1,
              'weekly_days' => '1',
              'end_times' => 1
            ]);
            
            $meeting->settings()->make([
              'join_before_host' => true,
              'approval_type' => 1,
              'registration_type' => 2,
              'enforce_login' => false,
              //'host_video' => false,
              //'participant_video' => false,
              //'mute_upon_entry' => false,
              'waiting_room' => true,
              //'approval_type' => config('zoom.approval_type'),
              //'audio' => config('zoom.audio'),
              //'auto_recording' => config('zoom.auto_recording'),
            ]);
            
            $result = $user->meetings()->save($meeting);
            //dd($result);
            $data['id'] = $result->id;
            $data['password'] = $result->password;
            return view('zoom',$data);
            dd(
                '<a href="https://kwctf.com/vue/laravel-vue-survey/public/zoom?zstart='.$result->id.'" target="_blank">start</a>',
                '<a href="https://kwctf.com/vue/laravel-vue-survey/public/zoom?zjoin='.$result->id.'" target="_blank">join</a>'
            );
     
        }
        
    }
    
    public function meetingsdkSignature(Request $request){
        //return '1254';
        //echo '123'; exit;
        $options = array(
			CURLOPT_RETURNTRANSFER => true,     // return web page
			CURLOPT_HEADER         => false,    // don't return headers
			CURLOPT_FOLLOWLOCATION => true,     // follow redirects
			CURLOPT_ENCODING       => "",       // handle all encodings
			CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:51.0) Gecko/20100101 Firefox/51.0", // who am i
			CURLOPT_AUTOREFERER    => true,     // set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
			CURLOPT_TIMEOUT        => 120,      // timeout on response
			CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
			CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks
			//CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
			//CURLOPT_PROXY => "103.234.254.166",
			//CURLOPT_PROXYPORT => "80",
		);
		
		$postValues= [
		    'meetingNumber' => $request->meetingNumber,
		    'role' => $request->role,
		    
		];
		
        $ch = curl_init("http://kwctf.com:3000");
		curl_setopt_array( $ch, $options );		
		curl_setopt($ch, CURLOPT_POST, count($postValues));
		curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postValues));		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: application/json',
			'Content-Length: ' . strlen(json_encode($postValues)))                                                                       
		);
		
		$content = curl_exec($ch);
		curl_close($ch);
		return $content;
    }


    public function createMeeting($postValues)
    {
        $user = Zoom::user()->first();

        $meeting = Zoom::meeting()->make([
            'topic' => $postValues['title'],
            'start_time' => new Carbon($postValues['start_time']),
            'duration' => 60,
            'timezone' => config('zoom.timezone'),
        ]);

        $meeting->settings()->make([
            'join_before_host' => false,
            'approval_type' => 1,
            'registration_type' => 2,
            'enforce_login' => false,
            'host_video' => false,
            'participant_video' => false,
            'mute_upon_entry' => true,
            'waiting_room' => true,
            'approval_type' => config('zoom.approval_type'),
            'audio' => config('zoom.audio'),
            'auto_recording' => config('zoom.auto_recording'),
        ]);

        $result = $user->meetings()->save($meeting);

        return response()->json($result);
    }
    
}