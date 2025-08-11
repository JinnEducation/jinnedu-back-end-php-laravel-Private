<?php

namespace App\Http\Controllers\Sections;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\Label;
use App\Models\Translation;

use Bouncer;
use Mail;

class SectionController extends Controller
{
    public $modelName;
    public $modelNameLang;
    public $modelTitle;
    public $modelFK;
    public $modelLangTitle;

    public function __construct()
    {
        $this->modelName = null;
        $this->modelNameLang = null;
        $this->modelTitle = '';
        $this->modelFK = '';
        $this->modelLangTitle = '';
    }

    public function index(Request $request, $id = 0)
    {

        if($id != 0) {
            $items = $this->modelName::find($id);
            if(!$items) {
                return response([
                        'success' => false,
                        'message' => 'item-dose-not-exist',
                        'msg-code' => '111'
                ], 200);
            }

            $items->langs = $items->langs()->get();
            $items->imageInfo;
            $items->iconInfo;
            $items->bannerInfo;

            $items->childrens = $items->childrens()->get();

            if($this->modelName == 'App\\Models\\Category') {
                $items->groupClasses = $items->groupClasses()->get();

            }

            foreach($items->childrens as $childrens) {
                $childrens->langs = $childrens->langs()->get();
                $childrens->imageInfo;
                $childrens->iconInfo;
                $childrens->bannerInfo;
            }
        } else {


            $limit = setDataTablePerPageLimit($request->limit);

            $items = $this->modelName::query($this->modelTitle . '.*');

            if($request->minmize == 'true') {
                $items->select($this->modelTitle . '.id', $this->modelTitle . '.name');
            }

            if($this->modelName == 'App\\Models\\Category' && !empty($request->type)) {
                $items->where('type', $request->type);
            }

            if(!empty($request->parent_id) && $request->parent_id == '-1') {
                $items->where('parent_id', 0);
                $limit = 1000;
            } elseif(!empty($request->parent_id)) { #
                $items->where('parent_id', intval($request->parent_id));
                $limit = 1000;
            }

            if(!empty($request->q)) {
                $items->leftJoin($this->modelLangTitle, $this->modelLangTitle . '.' . $this->modelFK, '=', $this->modelTitle . '.id');
                $items->whereRaw(filterTextDB($this->modelLangTitle . '.title') . ' like ?', ['%' . filterText($request->q) . '%']);
                $items->distinct();
            }

            //  $items = $items->paginate($limit);
            $items = paginate($items, $limit);

            //dd($items['data']);
            // var_dump($items[0]->name);exit;

            if(count($items) > 0) {
                foreach($items as $item) {

                    if($request->minmize == 'true') {
                        $item->langs = $item->langs()->select('language_id', 'slug', 'title', 'description', 'keywords')->get();
                    } else {
                        $item->langs = $item->langs()->get();
                    }

                    if($request->minmize != 'true') {
                        $item->imageInfo;
                        $item->iconInfo;
                        $item->bannerInfo;
                    }

                    $item->childrens = $item->childrens()->get();

                    if($this->modelName == 'App\\Models\\Category') {
                        $item->groupClasses = $item->groupClasses()->get();

                    }

                    foreach($item->childrens as $childrens) {
                        if($request->minmize == 'true') {
                            $childrens->langs = $childrens->langs()->select('language_id', 'slug', 'title', 'description', 'keywords')->get();
                        } else {
                            $childrens->langs = $childrens->langs()->get();
                        }

                        if($request->minmize != 'true') {
                            $childrens->imageInfo;
                            $childrens->iconInfo;
                            $childrens->bannerInfo;
                        }
                    }
                }
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
        // $data = $request->only(['name','parent_id','image','icon','banner','class','color','status','template','content_type','embed','metadata', 'type']);

        $baseFields = ['parent_id','image','icon','banner','class','color','status','template','content_type','embed','metadata'];
        $extraFields = ['type'];
        
        $data = $request->only(
            $this->modelName === \App\Models\Category::class
                ? array_merge($baseFields, $extraFields)
                : $baseFields
        );
        
        $langJson = json_encode($request->langs[0]);
        $lang = json_decode($langJson);
        $data['name'] = $lang->title;
                
        $itemDuplicated = $this->modelName::where('name', $data['name'])->where('id', '<>', $id)->first();
        if($itemDuplicated) {
            return response([
                    'success' => false,
                    'message' => 'item-duplicated2',
                    'msg-code' => '111'
            ], 500);
        }
        
        $user = Auth::user();
        $data['user_id'] = $user->id;
        $data['ipaddress'] = $request->ip();

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

        $this->setSectionLangs($item, $request->langs);

        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item
        ], 200);
    }

    public function setSectionLangs($item, $langs)
    {
        //$this->modelNameLang::where($this->modelFK,$item->id)->delete();
        if(!empty($langs) && count($langs) > 0) {
            foreach($langs as $langArray) {
                $langJson = json_encode($langArray);
                $lang = json_decode($langJson);
                $checkLang = $this->modelNameLang::where($this->modelFK, $item->id)->where('language_id', $lang->language_id)->first();
                $langData = [
                    $this->modelFK => $item->id,
                    'language_id' => $lang->language_id,
                    'slug' => isset($lang->slug) ? $lang->slug : '',
                    'title' =>  isset($lang->title) ? $lang->title : '',
                    'summary' =>  isset($lang->summary) ? $lang->summary : '',
                    'description' =>  isset($lang->description) ? $lang->description : '',
                    'keywords' =>  isset($lang->keywords) ? $lang->keywords : '',
                    'user_id' => $item->user_id,
                    'ipaddress' => $item->ipaddress
                ];

                if(!$checkLang) {
                    $this->modelNameLang::create($langData);
                    //echo 'in';
                } else {
                    $this->modelNameLang::where($this->modelFK, $item->id)->where('language_id', $lang->language_id)->update($langData);
                    //echo 'out';
                }
            }
        }
    }


    public function show($id)
    {
        $user = Auth::user();
        $item = $this->modelName::find($id);
        if(!$item) {
            return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        $item->langs = $item->langs()->get();
        $item->imageInfo;
        $item->iconInfo;
        $item->bannerInfo;

        $item->childrens = $item->childrens()->get();

        foreach($item->childrens as $childrens) {
            $childrens->langs = $childrens->langs()->get();
            $childrens->imageInfo;
            $childrens->iconInfo;
            $childrens->bannerInfo;
        }

        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $item
        ], 200);
    }

    public function destroy($id)
    {
        $user = Auth::user();
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
