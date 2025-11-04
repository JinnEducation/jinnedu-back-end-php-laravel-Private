<?php

function getSettingVal($key){
    $setting = \App\Models\Setting::whereRaw('id=? or name=?',[$key,$key])->first();
    if($setting) return $setting->value;
    return 'key-dose-not-exist';

}

function setDataTablePerPageLimit($limit){
    switch($limit){
            case '5': $limit = 5; break;
            case '10': $limit = 10; break;
            case '20': $limit = 20; break;
            case '30': $limit = 30; break;
            case '500': $limit = 500; break;
            default: $limit = 10; break;
        }
    return $limit;
}

function paginate($items, $limit) {

    $totalCount = count($items->get()); 

    $currentPage = request()->input('page', 1);  

    $items = $items->offset(($currentPage - 1) * $limit)->limit($limit)->get();

    $items = new \Illuminate\Pagination\LengthAwarePaginator(
        $items,
        $totalCount,
        $limit,
        $currentPage,
        ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
    );

    return $items;
}

function create_random_code($length = 8, $in_params = [])
{
    $in_params['upper_case']        = isset($in_params['upper_case']) ? $in_params['upper_case'] : true;
    $in_params['lower_case']        = isset($in_params['lower_case']) ? $in_params['lower_case'] : true;
    $in_params['number']            = isset($in_params['number']) ? $in_params['number'] : true;
    $in_params['special_character'] = isset($in_params['special_character']) ? $in_params['special_character'] : false;

    $chars = '';
    if ($in_params['lower_case']) {
        $chars .= "abcdefghijklmnopqrstuvwxyz";
    }

    if ($in_params['upper_case']) {
        $chars .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    }

    if ($in_params['number']) {
        $chars .= "0123456789";
    }

    if ($in_params['special_character']) {
        $chars .= "!@#$%^&*()_-=+;:,.";
    }

    return substr(str_shuffle($chars), 0, $length);
}

function checkLang($lang){
    if ($lang != 'ar') {
        $lang = 'en';
    }
    return $lang;
}

function locales(){
    return ['ar','en'];
}

function response_web($status, $message, $statusCode, $items = null)
{
    $response = ['status' => $status, 'message' => $message];
    if ($status && isset($items)) {
        $response['item'] = $items;
    } else {
        $response['errors_object'] = $items;
    }
    return response($response, $statusCode);
}

function filterText($text,$allow_space=false){

    $string=str_replace( 'أ', 'ا',str_replace( 'إ', 'ا',str_replace( 'آ', 'ا',str_replace( 'ة', 'ه',str_replace( 'ى','ي',$text)))));
    
    if(!$allow_space) $string=str_replace(  ' ', '%',$string);

    return $string;
     
}

function filterTextDB($colname,$allow_space=false){

      $string= " REPLACE( REPLACE( REPLACE( REPLACE( REPLACE( $colname, 'أ', 'ا'), 'إ', 'ا'), 'آ', 'ا'), 'ة', 'ه'), 'ى', 'ي')";

    if(!$allow_space) $string=" REPLACE( ".$string.", ' ', '') ";

    return $string;
     
}

function filterDataTable($items, $request,$take = null,$resource = null)
{
    if ($items->count() <= 0)
        return null;
    if (!$resource) {
        $resource = $items->first()->resource;
    }
    if (isset($take)) {
        $items = $items->take($take)->get();
        $data = $resource->collection($items);
        return $data;
    }
    $pagination = $request->pagination;
    $per_page = isset($pagination['perpage']) ? $pagination['perpage']: 10;
    $page = isset($pagination['page']) ? $pagination['page']: 1;
    if ($per_page == -1 || $per_page == null) {
        $per_page = 10;
    }
    $itemsCount = $items->count();
    $items = $items->take($per_page)->skip($per_page * ($page - 1))->get();
    $pagination['total'] = $itemsCount;
    $pagination['pages'] = ceil($itemsCount / $per_page);
    $data['meta'] = $pagination;
    $data['data'] = $resource::collection($items);
    return $data;
}

function uploadMedia($img,$validExtensions,$mainPath)
{
    $path = public_path('/');
    
    set_time_limit(300);
    if(!$img) return null;
    if (!$img->getClientOriginalExtension()){
        return null;
    }

    $file_type = strtolower($img->getClientOriginalExtension());
    if($file_type=='jpeg') $file_type='jpg';

    if(!in_array($file_type,$validExtensions)) return null;
    $fileName = $file_type . '-'.time().uniqid().'.' . $img->getClientOriginalExtension();
    $datePath = date('Y').'/'. date('m').'/'. date('d');
    $img->move($path.$mainPath.'/'.$datePath,$fileName);
    /*$data['url'] = '/images/'.$fileName;
    $data['type'] = $file_type;*/
    $optimizePath = optimizeImg($path.$mainPath.'/'.$datePath.'/' , $fileName , $file_type);
    //return str_replace('/home/jinnedu/public_html/server','',$optimizePath);
    return ['path'=>str_replace($path,'',$optimizePath), 'file'=>$fileName, 'name'=>$fileName, 'type'=> $file_type, 'extention'=>$file_type, 'size'=>0];
}

function uploadFile($img,$validExtensions,$mainPath)
{
    set_time_limit(300);
    $path = public_path('/');
    //dd($img);
    if(!$img) return null;
    if (!$img->getClientOriginalExtension()){
        return null;
    }

    $file_type = strtolower($img->getClientOriginalExtension());
    if($file_type=='jpeg') $file_type='jpg';

    if(!in_array($file_type,$validExtensions)) return null;
    $fileName = $file_type . '-'.time().uniqid().'.' . $img->getClientOriginalExtension();
    $datePath = date('Y').'/'. date('m').'/'. date('d');
    $img->move($path.$mainPath.'/'.$datePath,$fileName);
    /*$data['url'] = '/images/'.$fileName;
    $data['type'] = $file_type;*/
    $optimizePath = optimizeImg($path.$mainPath.'/'.$datePath.'/' , $fileName , $file_type);
    return str_replace($path,'',$optimizePath);
    //return '/'.$mainPath.'/'.$datePath.'/'.$fileName;
}

function optimizeImg($dir , $image , $file_type){
    $img = null;
    
    if($file_type=='png' && extension_loaded('gd') ) $img = imagecreatefrompng($dir . $image);
    
    else if($file_type=='jpg' && extension_loaded('gd') ) $img = imagecreatefromjpeg($dir . $image);
    
    if($img && extension_loaded('gd') ){
        $quality = 70;
        $newName = 'webp-'.time().uniqid().'.webp';
        imagewebp($img, $dir . $newName, $quality);
        imagedestroy($img);
        unlink($dir.$image);
        return $dir . $newName;
    }
    
    return $dir . $image;
}

function checkAllowFile($img,$validExtensions,$mainPath)
{
    //dd($img);
    if (!$img->getClientOriginalExtension()){
        return 'empty-img';
    }

    $file_type = strtolower($img->getClientOriginalExtension());
    if($file_type=='jpeg') $file_type='jpg';

    if(!in_array($file_type,$validExtensions)) return 'error-ext';
    
    return 'true';
}

function uploadImg($img)
{
    $path = public_path('/');
    set_time_limit(300);
    if(!$img) return null;
    if (!$img->getClientOriginalExtension()){
        return null;
    }
    $file_type = strtolower($img->getClientOriginalExtension());
    if($file_type=='jpeg') $file_type='jpg';
    $fileName = 'image-'.time().uniqid().'.' . $file_type;
    $img->move($path.'images',$fileName);
    $optimizePath = optimizeImg($path.'images/' , $fileName , $file_type);
    return str_replace($path,'',$optimizePath);
    $url = $path.'images/'.$fileName;
    return $url;
}

function imageExist($img , $default="uploads/no_images.jpg") {
    if(!empty($img) && \Storage::exists($img)) {
        return asset($img);
    }
    return asset($default);
}

function checkNull($key_name){
    return request()->has($key_name) && request()->$key_name != null && request()->$key_name != '';
}

function imageUrl($url){
    $url = asset($url);
    return $url;
}

function getLocale(){
    return app()->getLocale();
}

function registerLog($instance, $text='', $type='log')
{
    if ($instance instanceof Partner) {
        if ($type == 'log') {
            $instance->logs()->create(['log_text' => $text]);
        }elseif ($type == 'balance') {
            $instance->balances()->create(['log_text' => $text]);
        }else {

        }
    }elseif ($instance instanceof Estate){
        if ($type == 'log') {
            $instance->logs()->create(['log_text' => $text]);
        }else {

        }
    }else{

    }
}
function sendFCMNotification($title, $message, $token, $info = null)
{

    $server_key =  env('FIREBASE_SERVER_KEY', 'AAAAK9htOQw:APA91bHMUimKEXIIgbj1swvdmWKivgT1qT2kVpkYVmn6S6jOosI8UIKFzBrTEExLTpqNdvFGPkApd1UEtGD2dnkQSiFWkqkuUCN16HKt-ib7aQlmiffmhsJRb4QO5OvFI9cFyXBrqNWc');

    $data = [
        "registration_ids" => [$token],
        "notification" => [
            "title"     => $title,
            "body"      => $message,
            "sound"     => 'default',
        ],
        "data" => $info
    ];
    $encodedData = json_encode($data);

    $headers = [
        'Authorization:key=' . $server_key,
        'Content-Type: application/json',
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
    // Execute post
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }
    // Close connection
    curl_close($ch);
    // FCM response
    //dd($result);
}

if (! function_exists('label_text')) {
    /**
     * Retrieve a translated label stored via Labels Management.
     *
     * @param  string  $file    The label group/file name (e.g. "global").
     * @param  string  $name    The label key (e.g. "login").
     * @param  string|null $fallback Text to use if no translation is found.
     */
    function label_text(string $file, string $name, ?string $fallback = null): string
    {
        static $cache = [];

        $locale = strtolower((string) app()->getLocale());
        $cacheKey = $file.'|'.$name.'|'.$locale;

        if (array_key_exists($cacheKey, $cache)) {
            return $cache[$cacheKey];
        }

        if (
            ! \Illuminate\Support\Facades\Schema::hasTable('labels') ||
            ! \Illuminate\Support\Facades\Schema::hasTable('translations')
        ) {
            return $cache[$cacheKey] = $fallback ?? $name;
        }

        $language = \App\Models\Language::query()
            ->whereRaw('LOWER(shortname) = ?', [$locale])
            ->first();

        if (! $language) {
            return $cache[$cacheKey] = $fallback ?? $name;
        }

        $label = \App\Models\Label::query()
            ->where('file', $file)
            ->where('name', $name)
            ->first();

        if (! $label) {
            return $cache[$cacheKey] = $fallback ?? $name;
        }

        $translation = $label->translations()
            ->where('langid', $language->id)
            ->first();

        if ($translation && $translation->title) {
            return $cache[$cacheKey] = $translation->title;
        }

        return $cache[$cacheKey] = $fallback ?? $label->name ?? $name;
    }
}

