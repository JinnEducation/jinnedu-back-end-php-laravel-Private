<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use MacsiDigital\Zoom\Facades\Zoom;

// https://github.com/MacsiDigital/laravel-zoom
// https://github.com/zoom/meetingsdk-web-sample

class ZoomController extends Controller
{
    public function index(Request $request)
    {
        // https://success.zoom.us/wc/join/87973966307
        if (! empty($request->zstart)) {
            $data['url'] = 'https://us05web.zoom.us/wc/'.$request->zstart.'/start';

            return view('zoom', $data);
        } elseif (! empty($request->zjoin)) {
            $data['url'] = 'https://success.zoom.us/wc/join/'.$request->zjoin;

            return view('zoom', $data);
        } else {
            $user = Zoom::user()->first();
            // dd($user);
            // dd(config('zoom.auto_recording'));
            $meeting = Zoom::meeting()->make([
                'topic' => 'New meeting123',
                'type' => 8,
                'start_time' => new Carbon('2020-08-12 10:00:00'), // best to use a Carbon instance here.
            ]);

            $meeting->recurrence()->make([
                'type' => 2,
                'repeat_interval' => 1,
                'weekly_days' => '1',
                'end_times' => 1,
            ]);

            $meeting->settings()->make([
                'join_before_host' => true,
                'approval_type' => 1,
                'registration_type' => 2,
                'enforce_login' => false,
                // 'host_video' => false,
                // 'participant_video' => false,
                // 'mute_upon_entry' => false,
                'waiting_room' => true,
                // 'approval_type' => config('zoom.approval_type'),
                // 'audio' => config('zoom.audio'),
                // 'auto_recording' => config('zoom.auto_recording'),
            ]);

            $result = $user->meetings()->save($meeting);
            // dd($result);
            $data['id'] = $result->id;
            $data['password'] = $result->password;

            return view('zoom', $data);
            dd(
                '<a href="https://kwctf.com/vue/laravel-vue-survey/public/zoom?zstart='.$result->id.'" target="_blank">start</a>',
                '<a href="https://kwctf.com/vue/laravel-vue-survey/public/zoom?zjoin='.$result->id.'" target="_blank">join</a>'
            );

        }

    }

    public function meetingsdkSignature(Request $request)
    {
        // return '1254';
        // echo '123'; exit;
        $options = [
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING => '',       // handle all encodings
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:51.0) Gecko/20100101 Firefox/51.0', // who am i
            CURLOPT_AUTOREFERER => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT => 120,      // timeout on response
            CURLOPT_MAXREDIRS => 10,       // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks
            // CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            // CURLOPT_PROXY => "103.234.254.166",
            // CURLOPT_PROXYPORT => "80",
        ];

        $postValues = [
            'meetingNumber' => $request->meetingNumber,
            'role' => $request->role,

        ];

        $ch = curl_init('http://kwctf.com:3000');
        curl_setopt_array($ch, $options);
        curl_setopt($ch, CURLOPT_POST, count($postValues));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postValues));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: '.strlen(json_encode($postValues))]
        );

        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }

    // public function createMeeting($postValues)
    // {
    //     $user = Zoom::user()->first();

    //     $meeting = Zoom::meeting()->make([
    //         'topic' => $postValues['title'],
    //         'start_time' => new Carbon($postValues['start_time']),
    //         'duration' => 60,
    //         'timezone' => config('zoom.timezone'),
    //     ]);

    //     $meeting->settings()->make([
    //         'join_before_host' => false,
    //         'approval_type' => 1,
    //         'registration_type' => 2,
    //         'enforce_login' => false,
    //         'host_video' => false,
    //         'participant_video' => false,
    //         'mute_upon_entry' => true,
    //         'waiting_room' => true,
    //         'approval_type' => config('zoom.approval_type'),
    //         'audio' => config('zoom.audio'),
    //         'auto_recording' => config('zoom.auto_recording'),
    //     ]);

    //     $result = $user->meetings()->save($meeting);

    //     return response()->json($result);
    // }
    public function createMeeting($data)
    {
        // 1) توليد Access Token الجديد من Zoom
        $clientId = env('ZOOM_CLIENT_ID');
        $clientSecret = env('ZOOM_CLIENT_SECRET');
        $accountId = env('ZOOM_ACCOUNT_ID');

        // Zoom requires Base64(client_id:client_secret)
        $basicToken = base64_encode($clientId.':'.$clientSecret);

        // Generate Access Token
        $tokenResponse = Http::withHeaders([
            'Authorization' => 'Basic '.$basicToken,
        ])->asForm()->post('https://zoom.us/oauth/token', [
            'grant_type' => 'account_credentials',
            'account_id' => $accountId,
        ]);

        if (! $tokenResponse->successful()) {
            return [
                'success' => false,
                'message' => 'Could not generate access token from Zoom',
                'error' => $tokenResponse->json(),
            ];
        }

        $accessToken = $tokenResponse->json()['access_token'];

        // 2) إنشاء Meeting
        $response = Http::withToken($accessToken)->post('https://api.zoom.us/v2/users/me/meetings', [
            'topic' => $data['title'],
            'type' => 2, // Scheduled meeting
            'start_time' => $data['start_time'], // yyyy-mm-ddTHH:MM:SSZ
            'duration' => 60,
            'timezone' => 'UTC',
        ]);

        if (! $response->successful()) {
            return [
                'success' => false,
                'message' => 'Zoom meeting creation failed',
                'error' => $response->json(),
            ];
        }

        return [
            'success' => true,
            'data' => $response->json(),
        ];
    }
}
