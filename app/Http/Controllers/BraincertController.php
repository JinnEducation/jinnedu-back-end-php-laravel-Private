<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Conference;

use App\Models\GroupClass;
use App\Models\GroupClassDate;
use App\Models\GroupClassOutline;
use App\Models\GroupClassLang;

use App\Models\PrivateCourse;
use App\Models\PrivateCourseDate;
use App\Models\PrivateCourseLevel;
use App\Models\PrivateCourseLang;

class BraincertController extends Controller
{
    public $api_key;
    public $base_url;
    public $curl_options;
    
    function __construct() {
        $this->api_key = 'aBuDZuudBnxYt3LM2gsT';
        $this->base_url='https://testing.jinnedu.com';
        $this->curl_options = array(
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
        	//CURLOPT_PROXY => "0.0.0.0",
        	//CURLOPT_PROXYPORT => "80",
        );
    }
    
	public function conferenceCreate($postValues){
        $ch = curl_init($this->base_url."/v2/schedule?apikey=".$this->api_key);
        curl_setopt_array( $ch, $this->curl_options );		
        curl_setopt($ch, CURLOPT_POST, count($postValues));
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($postValues));		
        $output = curl_exec($ch);
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );		
        curl_close($ch);
        //var_dump($info );
        return $output;
	}
	
	public function conferenceLink($postValues){
        $ch = curl_init($this->base_url."/v2/getclasslaunch?apikey=".$this->api_key);
        curl_setopt_array( $ch, $this->curl_options );		
        curl_setopt($ch, CURLOPT_POST, count($postValues));
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($postValues));		
        $output = curl_exec($ch);
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );		
        curl_close($ch);
        //var_dump($info );
        return $output;
	}
}