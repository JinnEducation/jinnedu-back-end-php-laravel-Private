<?php

namespace App\Http\Controllers\Localizations;

use Illuminate\Http\Request;
use App\Http\Requests\Language\LanguageRequest;

use App\Models\User;
use App\Models\Language;
use App\Models\Translation;
use App\Models\Label;

use Bouncer;
use Mail;


class LanguageController extends LocalizationController
{
    public function index(Request $request)
    {
        $limit = setDataTablePerPageLimit($request->limit);
        
        $items = Language::query('languages.*');
         
         if(!empty($request->q)){
            $items->whereRaw(filterTextDB('languages.name').' like ?',['%'.filterText($request->q).'%']);
            $items->distinct();
        }
         
        // $items = $items->paginate($limit);
        $items = paginate($items, $limit);
        $data = [
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items
        ];
        
        $responsejson=json_encode($data);
        $gzipData=gzencode($responsejson,9);
        return response($gzipData)->withHeaders([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods'=> 'GET',            
            'Content-type' => 'application/json; charset=utf-8',
            'Content-Length'=> strlen($gzipData),
            'Content-Encoding' => 'gzip'
        ]);
        
        return response( $data , 200);
    }

    public function store(LanguageRequest $request)
    {
        $data = $request->only(['name','shortname','direction','dirword','icon','status','main']);
        $item = Language::create($data);
        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item
        ] , 200);
    }

    public function update(LanguageRequest $request, $id)
    {
        $item = Language::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        $data = $request->only(['name','shortname','direction','dirword','icon','status','main']);
        $item->update($data);
        return response([
                'success' => true,
                'message' => 'item-updated-successfully',
                'result' => $item
        ] , 200);
    }
    
    public function show($id)
    {
        $item = Language::find($id);
        if(!$item) return response([
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
        $item = Language::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        
        if($item->main==1){
          $item->main = 0;
          $item->save();  
          $this->setMain();
        }
        
        
        $item->delete();
        
        
        return response([
                'success' => true,
                'message' => 'item-deleted-successfully'
        ] , 200);
    }
    
    public function setMain($id=0)
    {
        if($id==0){
            $item = Language::where('main',0)->first();
        }else $item = Language::find($id);
        
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        Language::where('id','<>',$item->id)->update(['main' => 0]);
        
        $item->main = 1;
        $item->save();
        
        return response([
                'success' => true,
                'message' => 'item-setmain-successfully'
        ] , 200);
    }
    
    public function setLanguagesLabels(){
        return;
        $data = [
                ['1','english','languages','English','اللغة النجليزية','Englisch','Anglais'],
                ['2','arabic','languages','Arabic','اللغة العربية','Arabisch','Arabe'],
                ['3','albanian','languages','Albanian','اللغة الألبانية','Albanisch','Albanais'],
                ['4','armenian','languages','Armenian','اللغة الأرمنية','Armenisch','Arménien'],
                ['5','azerbaijani','languages','Azerbaijani','اللغة الأذربيجانية','Aserbaidschans','Azerbaïdjanais'],
                ['6','basque','languages','Basque','اللغة الباسكية','Personennamen','Basque'],
                ['7','bulgarian','languages','Bulgarian','اللغة البلغارية','Bulgarisch','Bulgare'],
                ['9','chinese','languages','Chinese (mandarin)','اللغة الصينية (الماندرين)','Mandarin-Leselernkarten','Chinois (mandarin)'],
                ['10','cebuano','languages','Cebuano','اللغة السيبيوانية ','Cebuano','Cebuano '],
                ['12','croatian','languages','Croatian','اللغة الكرواتية','Kroatisch','Croate'],
                ['13','czech','languages','Czech','اللغة التشيكية','Tschechisch','Tchèque'],
                ['14','danish','languages','Danish','اللغة الدنماركية','dänische','Danois'],
                ['15','german','languages','German','اللغة الألمانية','Deutsch','Allemand'],
                ['16','greek','languages','Greek','اللغة اليونانية','Griechisch','Grec'],
                ['18','farsi','languages','Farsi','اللغة الفارسية','Persisch','Persan'],
                ['19','finnish','languages','Finnish','اللغة الفنلندية','Finnisch','Finnois'],
                ['20','french','languages','French','اللغة الفرنسية','Französisch','Français'],
                ['23','hindi','languages','Hindi','اللغة الهندية','Hindi','Hindi'],
                ['24','hebrew','languages','Hebrew','اللغة العبريّة','Hebräisch','Hébraïque'],
                ['25','indonesian','languages','Indonesian','اللغة الإندونيسية','indonesische','Indonésien'],
                ['26','italian','languages','Italian','اللغة الايطالية','italienisch','Italien'],
                ['27','japanese','languages','Japanese','اللغة اليابانية','Japanische','Japonais'],
                ['29','korean','languages','Korean','اللغة الكورية','koreanisches','Coréen'],
                ['30','latin','languages','Latin','اللغة اللاتينية','Latein','Latin'],
                ['33','malay','languages','Malay','اللغة الماليزية','Malaysischen','Malaisien'],
                ['36','dutch','languages','Dutch','اللغة الهولندية','Holländisch','Néerlandais'],
                ['37','norwegian','languages','Norwegian','اللغة النرويجية','Norwegisch','Norvégien'],
                ['39','polish','languages','Polish','اللغة البولندية','polnische','Polonais'],
                ['40','portuguese','languages','Portuguese','اللغة البرتغالية','portugiesisch','Portugais'],
                ['41','romanian','languages','Romanian','اللغة الرومانية','römischen','Romain'],
                ['42','russian','languages','Russian','اللغة الروسية','Russisch','Russe'],
                ['47','spanish','languages','Spanish','اللغة الإسبانية','Spanisch','Espagnol'],
                ['48','swedish','languages','Swedish','اللغة السويدية','schwedisch','Euédois'],
                ['49','turkish','languages','Turkish','اللغة التركية','Türkisch','Turcique'],
                ['50','ukrainian','languages','Ukrainian','اللغة الأوكرانية','ukrainische','Ukrainien'],
                ['53','vietnamese','languages','Vietnamese','اللغة الفييتنامية','Vietnamesin','Vietnamien'],
                ['61','icelandic','languages','Icelandic','اللغة الآيسلندية','isländischen','Islandais'],
                ['62','irish','languages','Irish','اللغة الأيرلندية','irische','Irlandais'],
                ['72','thai','languages','Thai','اللغة التايلاندية','Thai','Thaïlandais']
            ];
            
            foreach($data as $item){
                $row = Language::find($item[0]);
                if($row){
                    $row->name = $item[1];
                    $row->save();
                    $req = json_decode('{"name":"'.$item[1].'", "file":"'.$item[2].'", "title":"'.$item[3].'"}');
                    //$trans = json_decode('[{"langid":"1", "title":"'.$item[3].'"},{"langid":"2", "title":"'.$item[4].'"}]',true);
                    $trans = json_decode('[{"langid":"1", "title":"'.$item[3].'"},{"langid":"2", "title":"'.$item[4].'"},{"langid":"20", "title":"'.$item[5].'"},{"langid":"15", "title":"'.$item[6].'"}]',true);
                    //var_dump($trans);exit;
                    $req->trans = $trans;
                    //var_dump($req);exit;
                    $this->addLabelAndTranslations($req);
                }
                
            }
    }
    
}