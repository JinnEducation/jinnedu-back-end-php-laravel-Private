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

use MuxPhp;
use \guzzlehttp\Client;


class MuxController extends Controller
{
    public function index(Request $request)
    {
         
         $config = MuxPhp\Configuration::getDefaultConfiguration()
                ->setUsername(getenv('MUX_TOKEN_ID'))
                ->setPassword(getenv('MUX_TOKEN_SECRET'));
        
            /*
            // API Client Initialization
            $assetsApi = new MuxPhp\Api\AssetsApi(
                new \GuzzleHttp\Client(),
                $config
            );
            
            
            // Create Asset Request
            $input = new MuxPhp\Models\InputSettings(["url" => "https://storage.googleapis.com/muxdemofiles/mux-video-intro.mp4"]);
            $createAssetRequest = new MuxPhp\Models\CreateAssetRequest(["input" => $input, "playback_policy" => [MuxPhp\Models\PlaybackPolicy::_PUBLIC] ]);
        
            // Ingest
            $result = $assetsApi->createAsset($createAssetRequest);
        
            // Print URL
            print "Playback URL: https://stream.mux.com/" . $result->getData()->getPlaybackIds()[0]->getId() . ".m3u8\n";
            */
            
            $liveApi = new MuxPhp\Api\LiveStreamsApi(
                new \GuzzleHttp\Client(),
                $config
            );

            $createAssetRequest = new MuxPhp\Models\CreateAssetRequest(["playback_policy" => [MuxPhp\Models\PlaybackPolicy::_PUBLIC]]);
            $createLiveStreamRequest = new MuxPhp\Models\CreateLiveStreamRequest(["playback_policy" => [MuxPhp\Models\PlaybackPolicy::_PUBLIC], "new_asset_settings" => $createAssetRequest]);
            $stream = $liveApi->createLiveStream($createLiveStreamRequest); 
            
            return $stream;
                     
         
    }

    
}