<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\Tutor;
use App\Models\User;
use App\Models\OurCourse;
use App\Models\OurCourseDate;
use App\Models\OurCourseLevel;
use App\Models\OurCourseLang;
use App\Models\OurCourseTutor;
use App\Models\GroupClass;
use Bouncer;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
class OurCourseController extends Controller
{
    public function tutorIndex(Request $request)
    {

        $user = auth()->user();

        $limit = setDataTablePerPageLimit($request->limit);

        if($user->type == 0){
            $items = OurCourse::whereHas('user', function($query){
                return $query->where('type', 2);
            })->where('status', $request->status??0)->newQuery();
        }else{
            $items = OurCourse::where('user_id', $user->id)->newQuery();
        }

        $items = $items->paginate($limit);

        foreach($items as $item) {
            $item->langs = $item->langs()->get();
            $item->levels = $item->levels()->get();
            if(count($item->levels) > 0) {
                foreach($item->levels as $level) {
                    $level->level;
                }
            }
            $item->rating = $item->reviews()->avg('stars');
            //$item->reviews = $item->reviews()->get();
            $item->user;
            // $item->imageInfo;
            $item->loadImageInfo($request->language_id ?? null);
        }


        return response([
               'success' => true,
               'message' => 'item-listed-successfully',
               'result' => $items
        ], 200);
    }

    public function mostPopular(Request $request)//$type
    {
        $limit = setDataTablePerPageLimit($request->limit);

        $topOurCoursesIds = Order::select('ref_id', \DB::raw('COUNT(user_id) as student_count'))
            ->where('ref_type', 2)
            ->groupBy('ref_id')
            ->orderByDesc('student_count')
            ->limit(3)
            ->pluck('ref_id')
            ->toArray();

        $ourCourses = OurCourse::whereIn('id', $topOurCoursesIds)->distinct();

        $ourCourses = $ourCourses->paginate($limit);

        foreach($ourCourses as $ourCourse) {
            $ourCourse->langs = $ourCourse->langs()->get();
            $ourCourse->levels = $ourCourse->levels()->get();
            if(count($ourCourse->levels) > 0) {
                foreach($ourCourse->levels as $level) {
                    $ourCourse->level;
                }
            }
            $ourCourse->rating = $ourCourse->reviews()->avg('stars');
            $ourCourse->tutors = $ourCourse->tutors()->get();
            if(isset($ourCourse->tutors) && count($ourCourse->tutors) > 0) {
                foreach($ourCourse->tutors as $tutor) {
                    $tutor->tutor;
                    if($tutor->tutor) {
                        $tutor->tutor->email = null;
                    }
                }
            }

            // $ourCourse->imageInfo;
            $ourCourse->loadImageInfo($request->language_id ?? null);
        }

                
        $data = [
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $ourCourses
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

    public function publishedCourses(Request $request, $slug=null){
        $user = Auth::user();

        if($slug != 0) {
            $items = OurCourse::where('slug', $slug)->first();
            if($items) {
                $items->category;
                if($items->category) {
                    $items->category->langs = $items->category->langs()->get();
                }
                $items->langs = $items->langs()->get();
                $items->levels = $items->levels()->get();
                if(count($items->levels) > 0) {
                    foreach($items->levels as $level) {
                        $level->level;
                    }
                }
                $items->rating = $items->reviews()->avg('stars');
                $items->reviews = $items->reviews()->get();
                if($items->reviews) {
                    foreach($items->reviews as $review) {
                        $review->user;
                        if($review->user) {
                            $review->user->email = null;
                        }
                    }
                }

                $items->user;
                // $items->imageInfo;
                $items->loadImageInfo($request->language_id ?? null);

                $suggestions = OurCourse::where('category_id', $items->category_id)->where('id', '<>', $items->id)->limit(6)->get();
                foreach($suggestions as $suggestion) {
                    if($items->category) {
                        $suggestion->category->langs = $suggestion->category->langs()->get();
                    }
                    $suggestion->langs = $suggestion->langs()->get();
                    $suggestion->levels = $suggestion->levels()->get();
                    if(count($suggestion->levels) > 0) {
                        foreach($suggestion->levels as $level) {
                            $level->level;
                        }
                    }
                    $suggestion->rating = $suggestion->reviews()->avg('stars');
                    $suggestion->reviews = $suggestion->reviews()->get();
                    if($suggestion->reviews) {
                        foreach($suggestion->reviews as $review) {
                            $review->user;
                            if($review->user) {
                                $review->user->email = null;
                            }
                        }
                    }
                    // $suggestion->imageInfo;
                    $suggestion->loadImageInfo($request->language_id ?? null);
                }

                $items->suggestions = $suggestions;
                $group_classes = GroupClass::with(['langs','category.langs','dates','imageInfo','tutor'])
                            ->where('category_id',$items->category_id)
                            ->orderBy('id','desc')->take(6)->get();
                            
                $items->group_classes = $group_classes;
            }
        } else {

            $limit = setDataTablePerPageLimit($request->limit);

            $items = OurCourse::where('status', 1)->newQuery();

            if(!empty($request->category_id)) {
                $items->where('category_id', $request->category_id);
            }

            $items = $items->paginate($limit);

            foreach($items as $item) {
                $item->langs = $item->langs()->get();
                $item->levels = $item->levels()->get();
                if(count($item->levels) > 0) {
                    foreach($item->levels as $level) {
                        $level->level;
                    }
                }
                $item->rating = $item->reviews()->avg('stars');

                $item->user;
                // $item->imageInfo;
                $item->loadImageInfo($request->language_id ?? null);
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

    public function index(Request $request, $id = 0)
    {
        return $this->listRequest($request, $id);
    }

    public function listRequest($request, $id = 0)
    {
        $user = Auth::user();

        if($id != 0) {
            $items = OurCourse::find($id);
            if($items) {
                $items->category;
                if($items->category) {
                    $items->category->langs = $items->category->langs()->get();
                }
                $items->langs = $items->langs()->get();
                $items->levels = $items->levels()->get();
                if(count($items->levels) > 0) {
                    foreach($items->levels as $level) {
                        $level->level;
                    }
                }
                $items->rating = $items->reviews()->avg('stars');
                $items->reviews = $items->reviews()->get();
                if($items->reviews) {
                    foreach($items->reviews as $review) {
                        $review->user;
                        if($review->user) {
                            $review->user->email = null;
                        }
                    }
                }
                // $items->tutors = $items->tutors()->get();
                // if(isset($items->tutors) && count($items->tutors) > 0) {
                //     foreach($items->tutors as $tutor) {
                //         $tutor->tutor;
                //         if($tutor->tutor) {
                //             $tutor->tutor->email = null;
                //         }
                //     }
                // }
                $items->user;
                // $items->imageInfo;
                $items->loadImageInfo($request->language_id ?? null);
                $items->attachment;
                $suggestions = OurCourse::where('category_id', $items->category_id)->where('id', '<>', $items->id)->limit(6)->get();
                foreach($suggestions as $suggestion) {
                    if($items->category) {
                        $suggestion->category->langs = $suggestion->category->langs()->get();
                    }
                    $suggestion->langs = $suggestion->langs()->get();
                    $suggestion->levels = $suggestion->levels()->get();
                    if(count($suggestion->levels) > 0) {
                        foreach($suggestion->levels as $level) {
                            $level->level;
                        }
                    }
                    $suggestion->rating = $suggestion->reviews()->avg('stars');
                    $suggestion->reviews = $suggestion->reviews()->get();
                    if($suggestion->reviews) {
                        foreach($suggestion->reviews as $review) {
                            $review->user;
                            if($review->user) {
                                $review->user->email = null;
                            }
                        }
                    }
                    // $suggestion->tutors = $suggestion->tutors()->get();
                    // if(isset($suggestion->tutors) && count($suggestion->tutors) > 0) {
                    //     foreach($suggestion->tutors as $tutor) {
                    //         $tutor->tutor;
                    //         if($tutor->tutor) {
                    //             $tutor->tutor->email = null;
                    //         }
                    //     }
                    // }
                    // $suggestion->imageInfo;
                    $suggestion->loadImageInfo($request->language_id ?? null);
                }

                $items->suggestions = $suggestions;
                $group_classes = GroupClass::with(['langs','category.langs','dates','imageInfo','tutor'])
                            ->where('category_id',$items->category_id)
                            ->orderBy('id','desc')->take(6)->get();
                            
                $items->group_classes = $group_classes;
            }
        } else {

            $limit = setDataTablePerPageLimit($request->limit);
            
            if($user->type == 0){
                $items = OurCourse::whereHas('user', function($query) {
                        $query->where('type', 0);
                    })->newQuery();
            }else{

                $items = OurCourse::whereHas('user', function($query) {
                        $query->where('type', 2);
                    })->where('status', 1)->newQuery();
            }

            if(!empty($request->category_id)) {
                $items->where('category_id', $request->category_id);
            }

            if(!empty($request->q)) {
                $items->whereRaw(
                    '(' . filterTextDB('name') . ' LIKE ?)', 
                    ['%' . filterText($request->q) . '%']
                );
            }

            $items = $items->paginate($limit);

            foreach($items as $item) {
                $item->langs = $item->langs()->get();
                $item->levels = $item->levels()->get();
                if(count($item->levels) > 0) {
                    foreach($item->levels as $level) {
                        $level->level;
                    }
                }
                $item->rating = $item->reviews()->avg('stars');
                //$item->reviews = $item->reviews()->get();
                // $item->tutors = $item->tutors()->get();
                // if(isset($item->tutors) && count($item->tutors) > 0) {
                //     foreach($item->tutors as $tutor) {
                //         $tutor->tutor;
                //         if($tutor->tutor) {
                //             $tutor->tutor->email = null;
                //         }
                //     }
                // }

                $item->user;
                // $item->imageInfo;
                $item->loadImageInfo($request->language_id ?? null);
                $item->attachment;
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
        dd($request->all());
        return $this->storeUpdateRequest($request);
    }

    public function update(Request $request, $id)
    {
        dd($request->all());
        return $this->storeUpdateRequest($request, $id);
    }

    public function storeUpdateRequest($request, $id = 0)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required|string|unique:our_courses,name,'. $id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 500);
        }
        
        $data = $request->only(['name','category_id','metadata','embed','attachment','status','publish','publish_date','images']);//'lessons','class_length','image',
        $user = Auth::user();
        $data['user_id'] = $user->id;
        $data['ipaddress'] = $request->ip();

        if($id > 0) {
            $item = OurCourse::find($id);
            if(!$item) {
                return response([
                        'success' => false,
                        'message' => 'item-dose-not-exist',
                        'msg-code' => '111'
                ], 200);
            }

            $data['image'] = $data['images'][0] ?? 0;

            $item->update($data);
        } else {
            $langJson = json_encode($request->langs[0]);
            $lang = json_decode($langJson);
            $data['name'] = $lang->title;
            $data['image'] = $data['images'][0] ?? 0;
            $item = OurCourse::create($data);
        }

        $this->setOurCourseLangs($item, $request->langs);
        //$this->setOurCourseLevels($item, $request->levels);
        // $this->setOurCourseTutors($item, $request->tutors);

        // $checkLevel = OurCourseLevel::where('our_course_id', $item->id)->where('level_id', $request->level_id)->first();
        // $levelData = [
        //     'our_course_id' => $item->id,
        //     'level_id' => $request->level_id,
        //     'user_id' => $item->user_id,
        //     'ipaddress' => $item->ipaddress
        // ];

        // if(!$checkLevel) {
        //     OurCourseLevel::create($levelData);
        // } else {
        //     OurCourseLevel::where('our_course_id', $item->id)->where('level_id', $request->level_id)->update($levelData);
        // }

        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item
        ], 200);
    }

    public function setOurCourseLangs($item, $langs)
    {
        //OurCourseLang::where('courseid',$item->id)->delete();
        if(!empty($langs) && count($langs) > 0) {
            foreach($langs as $langArray) {
                $langJson = json_encode($langArray);
                $lang = json_decode($langJson);
                $checkLang = OurCourseLang::where('our_course_id', $item->id)->where('language_id', $lang->language_id)->first();
                $langData = [
                    'our_course_id' => $item->id,
                    'language_id' => $lang->language_id,
                    'slug' => isset($lang->slug) ? $lang->slug : '',
                    'title' => isset($lang->title) ? $lang->title : '',
                    'about' => isset($lang->about) ? $lang->about : '',
                    'headline' => isset($lang->headline) ? $lang->headline : '',
                    'information' => isset($lang->information) ? $lang->information : '',
                    'user_id' => $item->user_id,
                    'ipaddress' => $item->ipaddress
                ];

                if(!$checkLang) {
                    OurCourseLang::create($langData);
                } else {
                    OurCourseLang::where('our_course_id', $item->id)->where('language_id', $lang->language_id)->update($langData);
                }
            }
        }
    }

    public function setOurCourseLevels($item, $levels)
    {
        //OurCourseLevel::where('course_id',$item->id)->delete();
        if(!empty($levels) && count($levels) > 0) {
            foreach($levels as $level) {
                $checkLevel = OurCourseLevel::where('our_course_id', $item->id)->where('level_id', $level['level_id'])->first();
                $levelData = [
                    'our_course_id' => $item->id,
                    'level_id' => $level['level_id'],
                    'user_id' => $item->user_id,
                    'ipaddress' => $item->ipaddress
                ];

                if(!$checkLevel) {
                    OurCourseLevel::create($levelData);
                } else {
                    OurCourseLevel::where('our_course_id', $item->id)->where('level_id', $level['level_id'])->update($levelData);
                }

            }
        }
    }

    public function setOurCourseTutors($item, $tutors)
    {
        OurCourseTutor::where('our_course_id',$item->id)->delete();
        if(!empty($tutors) && count($tutors) > 0) {
            foreach($tutors as $tutor) {
                $isTutor = Tutor::find($tutor['tutor_id']);
                if($isTutor) {
                    $checkTutor = OurCourseTutor::where('our_course_id', $item->id)->where('tutor_id', $tutor['tutor_id'])->first();
                    $tutorData = [
                        'our_course_id' => $item->id,
                        'tutor_id' => $tutor['tutor_id']
                    ];

                    if(!$checkTutor) {
                        OurCourseTutor::create($tutorData);
                    }
                }
            }
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $item = OurCourse::find($id);
        if(!$item) {
            return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        $item->category;
        if($item->category) {
            $item->category->langs = $item->category->langs()->get();
        }

        $item->langs = $item->langs()->get();
        $item->levels = $item->levels()->get();
        if(count($item->levels) > 0) {
            foreach($item->levels as $level) {
                $level->level;
            }
        }
        $item->rating = $item->reviews()->avg('stars');
        $item->reviews = $item->reviews()->get();
        if($item->reviews) {
            foreach($item->reviews as $review) {
                $review->user;
                if($review->user) {
                    $review->user->email = null;
                }
            }
        }
        $item->tutors = $item->tutors()->get();
        if(isset($item->tutors) && count($item->tutors) > 0) {
            foreach($item->tutors as $tutor) {
                $tutor->tutor;
                if($tutor->tutor) {
                    $tutor->tutor->email = null;
                }
            }
        }
        // $item->imageInfo;
        $item->loadImageInfo($request->language_id ?? null);
        $item->attachment;
        
        $suggestions = OurCourse::where('category_id', $item->category_id)->where('id', '<>', $item->id)->limit(6)->get();
        foreach($suggestions as $suggestion) {
            if($item->category) {
                $suggestion->category->langs = $suggestion->category->langs()->get();
            }
            $suggestion->langs = $suggestion->langs()->get();
            $suggestion->levels = $suggestion->levels()->get();
            if(count($suggestion->levels) > 0) {
                foreach($suggestion->levels as $level) {
                    $level->level;
                }
            }
            $suggestion->rating = $suggestion->reviews()->avg('stars');
            $suggestion->reviews = $suggestion->reviews()->get();
            if($suggestion->reviews) {
                foreach($suggestion->reviews as $review) {
                    $review->user;
                    if($review->user) {
                        $review->user->email = null;
                    }
                }
            }
            $suggestion->tutors = $suggestion->tutors()->get();
            if(isset($suggestion->tutors) && count($suggestion->tutors) > 0) {
                foreach($suggestion->tutors as $tutor) {
                    $tutor->tutor;
                    if($tutor->tutor) {
                        $tutor->tutor->email = null;
                    }
                }
            }
            // $suggestion->imageInfo;
            $suggestion->loadImageInfo($request->language_id ?? null);
        }

        $item->suggestions = $suggestions;

        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $item
        ], 200);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $item = OurCourse::find($id);
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
