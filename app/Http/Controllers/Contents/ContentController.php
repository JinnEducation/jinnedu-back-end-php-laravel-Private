<?php

namespace App\Http\Controllers\Contents;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\Post;
use App\Models\PostDepartment;
use App\Models\PostMedia;
use App\Models\Media;
use App\Models\PostPackage;
use App\Models\PostLang;

use Bouncer;
use Mail;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    public $modelName;
    public $modelTitle;
    public $content_type;

    public function __construct()
    {
        $this->modelName = null;
        $this->modelTitle = '';
        $this->content_type = 0;
    }

    public function index(Request $request, $id = 0)
    {
        //$user = Auth::user();
        $data = null;
        if($id != 0) {
            $item = Post::query('posts.*')->where('id', $id)->where('content_type', $this->content_type)->first();
            if($item) {
                $item->langs = $item->langs()->get();
                $item->media = $item->medias()->where('media_id','!=',$item->image)->get();
                $item->departments = $item->departments()->get();
                $item->imageInfo;
                $item->package = $item->package()->first();

                foreach($item->media as $media) {
                    $getmedia = Media::where('id', $media->id)->first();
                    $media->media = $getmedia;
                }
            }
            $data = [
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $item
            ];
        } else {

            $limit = setDataTablePerPageLimit($request->limit);

            $items = Post::select('posts.*')->where('content_type', $this->content_type);

            if(!empty($request->status)) $items->where('status',$request->status);
            
            if(!empty($request->q)) {
                $items->leftJoin('post_langs', 'post_langs.post_id', '=', 'posts.id');
                $items->whereRaw(filterTextDB('post_langs.title') . ' like ?', ['%' . filterText($request->q) . '%']);
                $items->distinct();
            }
            

            // $items = $items->paginate($limit);
            $items = paginate($items, $limit);
            
            if(count($items) > 0) {
                foreach($items as $item) {
                    $item->langs = $item->langs()->get();
                    $item->media = $item->medias()->where('media_id','!=',$item->image)->get();
                    $item->departments = $item->departments()->get();
                    $item->imageInfo;
                    $item->package = $item->package()->first();
                    foreach($item->media as $media) {
                        $getmedia = Media::where('id', $media->media_id)->first();
                        $media->media = $getmedia;
                    }
                    if($item->content_type == 5){
                        $item->start_date = $item->selected_dates[0] ?? null;
                        $item->end_date =  $item->selected_dates[count($item->selected_dates) - 1] ?? null;
                    }

                }
            }
            $data = [
                    'success' => true,
                    'message' => 'item-listed-successfully',
                    'result' => $items
            ];
        }

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
        $data = $request->only(['name','department_id','publish','publish_date','template','metadata','embed','status', 'link', 'selected_dates']);

        $itemDuplicated = Post::where('name', $data['name'])->where('content_type', $this->content_type)->where('id', '<>', $id)->first();
        if($itemDuplicated) {
            return response([
                    'success' => false,
                    'message' => 'item-duplicated2',
                    'msg-code' => '111'
            ], 200);
        }

        $user = Auth::user();
        $data['user_id'] = $user->id;
        $data['ipaddress'] = $request->ip();
        $data['content_type'] = $this->content_type;
        $data['slug'] = Str::slug($request->name);
        
        // update
        if($id > 0) {
            $item = Post::find($id);
            if(!$item) {
                return response([
                        'success' => false,
                        'message' => 'item-dose-not-exist',
                        'msg-code' => '111'
                ], 200);
            }
            $item->update($data);
        } else {
            $item = Post::create($data);
        }

        $this->setPostDepartments($item, $request->departments);
        $this->setPostMedia($item, $request->media, $request->langs);
        $this->setPostLangs($item, $request->langs);
        $this->setPostPackage($item, $request->package);

        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item
        ], 200);
    }

    public function setPostDepartments($item, $departments)
    {
        PostDepartment::where('post_id', $item->id)->delete();
        if(!empty($departments) && count($departments) > 0) {
            foreach($departments as $department) {
                PostDepartment::create([
                    'post_id' => $item->id,
                    'department_id' => $department['department_id'],
                    'user_id' => $item->user_id,
                    'ipaddress' => $item->ipaddress
                ]);
            }
        }
    }

    public function setPostMedia($item, $media, $langs)
    {
        $langJson = json_encode($langs);
        $lang = json_decode($langJson);
        PostMedia::where('post_id', $item->id)->delete();
        if(!empty($media) && count($media) > 0) {
            $i = 0;
            foreach($media as $file) {
                $i++;
                PostMedia::create([
                    'post_id' => $item->id,
                    'media_id' => $file['media_id'],
                    'user_id' => $item->user_id,
                    'language_id' => 1,
                    'ipaddress' => $item->ipaddress
                ]);
                if($i == 1) {
                    $item->image = $file['media_id'];
                    $item->save();
                }
            }
        }
    }

    public function setPostPackage($item, $package)
    {
        //PostPackage::where('post_id',$item->id)->delete();
        if($this->content_type == 11) {
            $checkPkg = PostPackage::where('post_id', $item->id)->first();
            $pkgData = [
                'post_id' => $item->id,
                'price' => $package['price'],
                'group_class_count' => $package['group_class_count'],
                // 'our_course_count' => $package['our_course_count'],
                'private_lesson_count' => $package['private_lesson_count'],
                'user_id' => $item->user_id
            ];

            if(!$checkPkg) {
                PostPackage::create($pkgData);
            } else {
                PostPackage::where('post_id', $item->id)->update($pkgData);
            }
        }

    }

    public function setPostLangs($item, $langs)
    {
        //PostLang::where('post_id',$item->id)->delete();
        if(!empty($langs) && count($langs) > 0) {
            foreach($langs as $langArray) {
                $langJson = json_encode($langArray);
                $lang = json_decode($langJson);
                $checkLang = PostLang::where('post_id', $item->id)->where('language_id', $lang->language_id)->first();
                $langData = [
                    'post_id' => $item->id,
                    'language_id' => $lang->language_id,
                    'slug' => isset($lang->slug) ? $lang->slug : '',
                    'title' => isset($lang->title) ? $lang->title : '',
                    'summary' => isset($lang->summary) ? $lang->summary : '',
                    'description' => isset($lang->description) ? $lang->description : '',
                    'keywords' => isset($lang->keywords) ? $lang->keywords : '',
                    'user_id' => $item->user_id,
                    'ipaddress' => $item->ipaddress
                ];

                if(!$checkLang) {
                    PostLang::create($langData);
                } else {
                    PostLang::where('post_id', $item->id)->where('language_id', $lang->language_id)->update($langData);
                }
            }
        }
    }


    public function show($id)
    {
        $user = Auth::user();
        $item = Post::find($id);
        if(!$item) {
            return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        $item->langs = $item->langs()->get();
        $item->media = $item->medias()->get();
        $item->departments = $item->departments()->get();
        $item->imageInfo;

        if($this->content_type == 11) {
            $item->package = $item->package()->first();
        }

        foreach($item->media as $media) {
            $media->media;
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
        $item = Post::find($id);
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