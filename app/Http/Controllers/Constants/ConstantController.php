<?php

namespace App\Http\Controllers\Constants;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Label;
use App\Models\Translation;

use Bouncer;
use Mail;

class ConstantController extends Controller
{
    public $modelName;
    public $modelTitle;
    public $curl_options;

    public function __construct()
    {
        $this->modelName = null;
        $this->modelTitle = '';
    }

    public function index(Request $request)
    {
        $limit = setDataTablePerPageLimit($request->limit);
        $items = $this->modelName::query($this->modelTitle . '.*');
        if(!empty($request->q)) {
            $items->leftJoin('labels', 'labels.name', '=', $this->modelTitle . '.name');
            $items->leftJoin('translations', 'translations.labelid', '=', 'labels.id');
            $items->where('labels.file', $this->modelTitle);
            //$items->where('translations.title','Afghanistan');
            $items->whereRaw(filterTextDB('translations.title') . ' like ?', ['%' . filterText($request->q) . '%']);
            $items->distinct();
        }
        if($request->has('exams') && $request->exams == 1){
            $items->where('level_number', '>', 1);
        }
    

        //dd( $items->toSql() );

        // $items = $items->paginate($limit);
        $items = paginate($items, $limit);
        
        foreach($items as $item){
            
            $item->trans = null;
            $name = str_replace(' ', '-', strtolower($item->name));
            $label = Label::where('name', $name)->where('file', $this->modelTitle)->first();
            if($label) {
                $item->trans = $label->translations()->get();
            }
        
        }

        $data = [
               'success' => true,
               'message' => 'item-listed-successfully',
               'result' => $items
        ];

        $responsejson = json_encode($data);
        $gzipData = gzencode($responsejson, 9);
        return response($gzipData)->withHeaders([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET',
            'Content-type' => 'application/json; charset=utf-8',
            'Content-Length' => strlen($gzipData),
            'Content-Encoding' => 'gzip'
        ]);

        return response($data, 200);
    }

    public function store(Request $request)
    {
        return $this->storeUpdateRequest($request);
    }

    public function update(Request $request, $id)
    {
        return $this->storeUpdateRequest($request, $id);
    }

    public function storeUpdateRequest($request, $id = 0)
    {
        if($request->has('level_number')){
            $data = $request->only(['name','level_number']);
        }else{
            $data = $request->only(['name']);
        }
        $data['name'] = str_replace(' ', '-', strtolower($data['name']));
        if(isset($data['level_number'])){
            $itemDuplicated = $this->modelName::where('name', $data['name'])->where('level_number', $data['level_number'])->where('id', '<>', $id)->first();
            if($itemDuplicated) {
                return response([
                        'success' => false,
                        'message' => 'item-duplicated2',
                        'msg-code' => '111'
                ], 200);
            }
        }else{
            $itemDuplicated = $this->modelName::where('name', $data['name'])->where('id', '<>', $id)->first();
            if($itemDuplicated) {
                return response([
                        'success' => false,
                        'message' => 'item-duplicated2',
                        'msg-code' => '111'
                ], 200);
            }
        }


        $item = null;

        if($id > 0) {
            $item = $this->modelName::find($id);
            if(!$item) {
                return response([
                        'success' => false,
                        'message' => 'item-dose-not-exist',
                        'msg-code' => '111'
                ], 200);
            }
            $item->update($data);
        } else {
            $item = $this->modelName::create($data);
        }

        if(isset($data['level_number'])){
            $req = json_decode('{"name":"'.$data['name'].'", "file":"'.$this->modelTitle.'", "title":"'.$data['name'].'", "level_number":"'.$data['level_number'].'"}');
        }else{
            $req = json_decode('{"name":"'.$data['name'].'", "file":"'.$this->modelTitle.'", "title":"'.$data['name'].'"}');
        }
        $req->trans = $request->trans;
        $this->addLabelAndTranslations($req);

        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item
        ], 200);
    }

    public function show($id)
    {
        $item = $this->modelName::find($id);
        if(!$item) {
            return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        $item->trans = null;
        $name = str_replace(' ', '-', strtolower($item->name));
        $label = Label::where('name', $name)->where('file', $this->modelTitle)->first();
        if($label) {
            $item->trans = $label->translations()->get();
        }

        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $item
        ], 200);
    }

    public function destroy($id)
    {
        $item = $this->modelName::find($id);
        if(!$item) {
            return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        $item->delete();


        return response([
                'success' => true,
                'message' => 'item-deleted-successfully'
        ], 200);
    }

}