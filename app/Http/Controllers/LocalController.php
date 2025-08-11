<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\Translation;
use App\Models\Label;
use App\Models\Language;

use Bouncer;
use Mail;
use filemtime;


class LocalController extends Controller
{
    
    public function locallLang($lang){
        $language = Language::where('shortname',$lang)->first();
        if(!$language) return []; 
                        
        $labels = Label::all();
        $translations=[];
        foreach($labels as $label){
            $translation = $label->translations()->where('langid',$language->id)->first();
            $translations[$label->file][$label->name] = isset($translation->title)?$translation->title:$label->name; 
        }
        return $translations;
    }
    
    public function localesLang($lang){
        $local = Translation::select('updated_at')->orderBy('updated_at','DESC')->first();
        
        
        // if (file_exists('/home/jinnedu/public_html/jinntest.jinnedu.com/server/static/locales/lang/'.$lang.'.php')) {
        //     if(date('Y-m-d H:i:s',filemtime('/home/jinnedu/public_html/jinntest.jinnedu.com/server/static/locales/lang/'.$lang.'.php')) >= $local->updated_at ){
        //         include '/home/jinnedu/public_html/jinntest.jinnedu.com/server/static/locales/lang/'.$lang.'.php';
        //         $responsejson=$lang;
        //         $gzipData=gzencode($responsejson,9);
        //         return response($gzipData)->withHeaders([
        //             'Access-Control-Allow-Origin' => '*',
        //             'Access-Control-Allow-Methods'=> 'GET',            
        //             'Content-type' => 'application/json; charset=utf-8',
        //             'Content-Length'=> strlen($gzipData),
        //             'Content-Encoding' => 'gzip'
        //         ]);
        //         return json_decode($lang);
        //         exit;
        //     }
        // }
        $language = Language::where('shortname',$lang)->first();
        if(!$language) return []; 
                        
        $labels = Label::all();
        $translations=[];
        foreach($labels as $label){
            
            $translation = $label->translations()->where('langid',$language->id)->first();
            if($label->name == 'become-a-tutor'){
                // return 2;
                // return $translation;
            }
            $title = '';
            if($translation){
               $title = $translation->title;
            }else{
                $title = $label->name;
            }
            if($translation){
            $translations[$label->file][$label->name] = $title;
         }
        }
        
        if (file_exists('/home/jinnedu/public_html/jinntest.jinnedu.com/server/static/locales/lang/'.$lang.'.php'))
            unlink('/home/jinnedu/public_html/jinntest.jinnedu.com/server/static/locales/lang/'.$lang.'.php');
        $myfile = fopen('/home/jinnedu/public_html/jinntest.jinnedu.com/server/static/locales/lang/'.$lang.'.php', "w") or die("Unable to open file!");
        $translations['static']=true;
        $txt = "<?php\n".
        //"header('Content-Type: application/json; charset=utf-8');\n".
        //"\$filename = '/home/jinnedu/public_html/server/static/locales/lang/".$lang.".php';\n".
        //"\$file_time = date(\"Y-m-d H:i:s.\", filemtime(\$filename));\n".
        "\$lang = '".str_replace("'",'',json_encode($translations))."';\n".
        //"echo \$lang;\n".
        "?>";
        $translations['static']=false;

        //echo $txt;exit;
        fwrite($myfile, $txt);
        fclose($myfile);
        
        $responsejson=json_encode($translations);
        $gzipData=gzencode($responsejson,9);
        return response($gzipData)->withHeaders([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods'=> 'GET',            
            'Content-type' => 'application/json; charset=utf-8',
            'Content-Length'=> strlen($gzipData),
            'Content-Encoding' => 'gzip'
        ]);
        
        return $translations;
    }
    
    public function localesLangs(){
        $local = Language::select('updated_at')->orderBy('updated_at','DESC')->first();
        if (file_exists('/home/jinnedu/public_html/jinntest.jinnedu.com/server/static/locales/langs.php')) {
            if(date('Y-m-d H:i:s',filemtime('/home/jinnedu/public_html/jinntest.jinnedu.com/server/static/locales/langs.php')) >= $local->updated_at ){
                include '/home/jinnedu/public_html/jinntest.jinnedu.com/server/static/locales/langs.php';
                $responsejson=$langs;
                $gzipData=gzencode($responsejson,9);
                return response($gzipData)->withHeaders([
                    'Access-Control-Allow-Origin' => '*',
                    'Access-Control-Allow-Methods'=> 'GET',            
                    'Content-type' => 'application/json; charset=utf-8',
                    'Content-Length'=> strlen($gzipData),
                    'Content-Encoding' => 'gzip'
                ]);
                return json_decode($langs);
                exit;
            }
        }
        
        $langs = Language::where('status',1)->get();
        
        
        $data = [
            'success' => true,
            'data'=> $langs
        ];
        
        if (file_exists('/home/jinnedu/public_html/jinntest.jinnedu.com/server/static/locales/langs.php'))
            unlink('/home/jinnedu/public_html/jinntest.jinnedu.com/server/static/locales/langs.php');
        $myfile = fopen('/home/jinnedu/public_html/jinntest.jinnedu.com/server/static/locales/langs.php', "w") or die("Unable to open file!");
        $data['static']=true;
        $txt = "<?php\n".
        //"header('Content-Type: application/json; charset=utf-8');\n".
        //"\$filename = '/home/jinnedu/public_html/server/static/locales/langs.php';\n".
        //"\$file_time = date(\"Y-m-d H:i:s.\", filemtime(\$filename));\n".
        "\$langs = '".str_replace("'",'',json_encode($data))."';\n".
        //"echo \$langs;\n".
        "?>";
        $data['static']=false;

        //echo $txt;exit;
        fwrite($myfile, $txt);
        fclose($myfile);
        
        $responsejson=json_encode($data);
        $gzipData=gzencode($responsejson,9);
        return response($gzipData)->withHeaders([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods'=> 'GET',            
            'Content-type' => 'application/json; charset=utf-8',
            'Content-Length'=> strlen($gzipData),
            'Content-Encoding' => 'gzip'
        ]);
        
        return response($data);
    }
    
}