<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\ConferenceAttendance;
use App\Models\GroupClass;
use App\Models\GroupClassDate;
use App\Models\GroupClassLang;
use App\Models\GroupClassOutline;
use App\Models\GroupClassStudent;
use App\Models\GroupClassTutor;
use App\Models\Order;
use App\Models\Tutor;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GroupClassController extends Controller
{
    public function registerAsTutor(Request $request, $group_class_id)
    {
        $user = Auth::user() ?? $request->user()->id;
        
        $tutor = Tutor::find($user->id);

        if (! $tutor) {
            return response([
                'success' => false,
                'message' => 'tutor-dose-not-exist',
                'msg-code' => '111',
            ], 200);
        }

        $group_class = GroupClass::find($group_class_id);
        if (! $group_class) {
            return response([
                'success' => false,
                'message' => 'groub-class-dose-not-exist',
                'msg-code' => '222',
            ], 200);
        }

        $checkTutor = GroupClassTutor::where('group_class_id', $group_class->id)->where('tutor_id', $tutor->id)->first();
        $tutorData = [
            'group_class_id' => $group_class->id,
            'tutor_id' => $tutor->id,
        ];

        if ($checkTutor) {
            return response([
                'success' => false,
                'message' => 'already-registered',
                'msg-code' => '333',
            ], 200);
        }

        GroupClassTutor::create($tutorData);

        return response([
            'success' => true,
            'message' => 'registered-done',
        ], 200);
    }

    public function unRegisterAsTutor(Request $request, $group_class_id)
    {
        $user = Auth::user();

        $tutor = Tutor::find($user->id);
        if (! $tutor) {
            return response([
                'success' => false,
                'message' => 'tutor-dose-not-exist',
                'msg-code' => '111',
            ], 200);
        }

        $group_class = GroupClass::find($group_class_id);
        if (! $group_class) {
            return response([
                'success' => false,
                'message' => 'groub-class-dose-not-exist',
                'msg-code' => '222',
            ], 200);
        }

        $checkTutor = GroupClassTutor::where('group_class_id', $group_class->id)->where('tutor_id', $tutor->id)->first();

        if (! $checkTutor) {
            return response([
                'success' => false,
                'message' => 'already-un-registered',
                'msg-code' => '333',
            ], 200);
        }

        GroupClassTutor::where('group_class_id', $group_class->id)->where('tutor_id', $tutor->id)->delete();

        return response([
            'success' => true,
            'message' => 'un-registered-done',
        ], 200);
    }

    public function groupClassTutors(Request $request, $group_class_id)
    {
        $limit = setDataTablePerPageLimit($request->limit);

        $group_class = GroupClass::find($group_class_id);
        if (! $group_class) {
            return response()->json([
                'success' => false,
                'message' => 'groub-class-dose-not-exist',
                'msg-code' => '222',
            ], 200);
        }

        $groupClassTutorsIds = GroupClassTutor::where('group_class_id', $group_class->id)->pluck('tutor_id')->toArray();

        $items = Tutor::whereIn('id', $groupClassTutorsIds);

        $items = paginate($items, $limit);


        foreach($items as $tutor){

            $tutor->price = optional($tutor->hourlyPrices()?->first())->price ?? 0;
            $tutor->rating = $tutor->reviews()->avg('stars');

            $tutor->specialization = $tutor->descriptions()->first()->specialization ?? '';

        }

        $data = [
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items,
        ];

        $responsejson = json_encode($data);
        $gzipData = gzencode($responsejson, 9);

        return response($gzipData)->withHeaders([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET',
            'Content-type' => 'application/json; charset=utf-8',
            'Content-Length' => strlen($gzipData),
            'Content-Encoding' => 'gzip',
        ]);

        return response($data, 200);
    }

    public function assignTutorToGroupClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_class_id' => 'required|exists:group_classes,id',
            'tutor_id' => 'required|exists:tutors,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 200);
        }

        $group_class = GroupClass::findOrFail($request->group_class_id);
        if ($group_class->tutor_id != null) {
            return response()->json([
                'success' => false,
                'message' => 'The group class already has an assigned tutor.',
                'msg-code' => '222',
            ], 200);
        }

        $checkTutor = GroupClassTutor::where('group_class_id', $group_class->id)->where('tutor_id', $request->tutor_id)->first();

        if (! $checkTutor) {
            return response([
                'success' => false,
                'message' => 'The tutor is not registered for this group class.',
                'msg-code' => '222',
            ], 200);
        }

        $group_class->update(['tutor_id' => $request->tutor_id]);

        GroupClassTutor::where('group_class_id', $group_class->id)->where('tutor_id', $request->tutor_id)->update(['status' => 'approved']);

        GroupClassTutor::where('group_class_id', $group_class->id)->where('tutor_id', '<>', $request->tutor_id)->update(['status' => 'rejected']);

        Conference::where('ref_type', 1)->where('ref_id', $group_class->id)->update(['tutor_id' => $request->tutor_id]);

        // إرسال إشعار للطلاب المسجلين بتعيين المدرّس
        $studentIds = GroupClassStudent::where('class_id', $group_class->id)->pluck('student_id');
        $tutor = User::find($request->tutor_id);
        if($tutor && $studentIds->isNotEmpty()) {
            foreach($studentIds as $studentId) {
                $student = User::find($studentId);
                if($student && $student->fcm) {
                    $info = [
                        'type' => 'tutor_assigned',
                        'group_class_id' => $group_class->id,
                        'tutor_name' => $tutor->name
                    ];
                    sendFCMNotification(
                        'Tutor Assigned', 
                        'A tutor has been assigned to your group class: ' . $group_class->name, 
                        $student->fcm, 
                        $info
                    );
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Tutor assigned successfully.',
        ], 200);

    }

    public function unAssignTutorToGroupClass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_class_id' => 'required|exists:group_classes,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 200);
        }

        $group_class = GroupClass::findOrFail($request->group_class_id);
        if ($group_class->tutor_id == null) {
            return response()->json([
                'success' => false,
                'message' => 'The group class doesn\'t have a tutor.',
                'msg-code' => '222',
            ], 200);
        }

        // حفظ معلومات المدرّس قبل الحذف للإشعار
        $oldTutorId = $group_class->tutor_id;
        
        GroupClassTutor::where('group_class_id', $group_class->id)->where('tutor_id', $group_class->tutor_id)->update(['status' => 'rejected']);

        Conference::where('ref_type', 1)->where('ref_id', $group_class->id)->update(['tutor_id' => null]);

        $group_class->update(['tutor_id' => null]);

        // إرسال إشعار للطلاب المسجلين بإلغاء تعيين المدرّس
        $studentIds = GroupClassStudent::where('class_id', $group_class->id)->pluck('student_id');
        if($studentIds->isNotEmpty()) {
            foreach($studentIds as $studentId) {
                $student = User::find($studentId);
                if($student && $student->fcm) {
                    $info = [
                        'type' => 'tutor_unassigned',
                        'group_class_id' => $group_class->id
                    ];
                    sendFCMNotification(
                        'Tutor Removed', 
                        'The tutor has been removed from your group class: ' . $group_class->name, 
                        $student->fcm, 
                        $info
                    );
                }
            }
        }
        
        // إرسال إشعار للمدرّس السابق
        $oldTutor = User::find($oldTutorId);
        if($oldTutor && $oldTutor->fcm) {
            $info = [
                'type' => 'removed_from_class',
                'group_class_id' => $group_class->id
            ];
            sendFCMNotification(
                'Removed from Class', 
                'You have been removed from group class: ' . $group_class->name, 
                $oldTutor->fcm, 
                $info
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Tutor un assigned successfully.',
        ], 200);

    }

    public function getAssignedGroupClass(Request $request, $slug = null)
    {
        $user = Auth::user();

        // in update process
        if ($slug != null) {
            $items = GroupClass::where('slug', $slug)->where('tutor_id', '<>', null)->first();
            if ($items) {
                $items->level;
                $items->category;
                if ($items->category) {
                    $items->category->langs = $items->category->langs()->get();
                }
                $items->langs = $items->langs()->get();
                $items->dates = $items->dates()->get();
                $items->reviews = $items->reviews()->get();
                if ($items->reviews) {
                    foreach ($items->reviews as $review) {
                        $review->user;
                        if ($review->user) {
                            $review->user->email = null;
                        }
                    }
                }
                $items->rating = $items->reviews()->avg('stars');
                $items->tutor;

                                
                $items->tutor->price =  $items->tutor->hourlyPrices()?->first()?->price ?? 0;


                $items->tutor->price = $items->tutor->hourlyPrices()?->first()?->price ?? 0;

                $tutor_about = $items->tutor->abouts()->first();
                if ($tutor_about) {
                    $items->tutor->language = $tutor_about->language()->first()->name ?? null;
                    $items->tutor->subject = $tutor_about->subject()->first()->name ?? null;
                } else {
                    $items->tutor->language = null;
                    $items->tutor->subject = null;
                }

                $items->tutor->videos = $items->tutor->videos()->get();

                if ($items->tutor) {
                    $items->tutor->email = null;
                }
                /*$items->outlines = $items->outlines()->get();

                foreach($items->outlines as $outline){
                    $outline->outline;
                    $outline->dates = $outline->dates()->get();

                    foreach($outline->dates as $date) $date->tutor;
                }*/

                $items->hasExam = $items->exams->count() ? 1 : 0;
                // $items->isOrdered = Order::where(['user_id' => $user->id , 'ref_type' => 1, 'ref_id' => $items->id])->count() ? 1 : 0;

                $items->imageInfo;
                $items->attachment;

                $suggestions = GroupClass::where('category_id', $items->category_id)->where('id', '<>', $items->id)->where('tutor_id', '<>', null)->where('status', 1)->limit(6)->get();
                foreach ($suggestions as $suggestion) {
                    $suggestion->level;
                    $suggestion->category;
                    if ($suggestion->category) {
                        $suggestion->category->langs = $suggestion->category->langs()->get();
                    }
                    $suggestion->langs = $suggestion->langs()->get();
                    $suggestion->dates = $suggestion->dates()->get();
                    // $suggestion->reviews = $suggestion->reviews()->get();
                    $suggestion->rating = $suggestion->reviews()->avg('stars');
                    $suggestion->tutor;
                    if ($suggestion->tutor) {
                        $suggestion->tutor->email = null;
                    }

                    /*$item->students = $item->students()->get();
                    foreach($item->students as $student){
                        $outline->email=null;
                    }*/

                    /*$item->outlines = $item->outlines()->get();
                    foreach($item->outlines as $outline){
                        $outline->outline;
                        $outline->dates = $outline->dates()->get();

                        foreach($outline->dates as $date) $date->tutor;
                    }*/

                    $suggestion->imageInfo;
                }

                $items->suggestions = $suggestions;
            } else {
                return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111',
                ], 200);
            }
        } else {

            $limit = setDataTablePerPageLimit($request->limit);

            // get all group classes
            $items = GroupClass::where('group_classes.status', 1)->where('tutor_id', '<>', null)->select('group_classes.*');
            // join group class langs
            $items->leftjoin('group_class_langs', 'group_class_langs.classid', 'group_classes.id');

            if (! empty($request->tutor_id)) {
                $items->where('tutor_id', $request->tutor_id);
            }

            if (! empty($request->category_id)) {
                $items->where('category_id', $request->category_id);
            }
            if (! empty($request->category)) {
                $items->where('category_id', $request->category);
            }

            if (! empty($request->price_from)) {
                $items->where('price', '>=', $request->price_from);
            }

            if (! empty($request->price_to)) {
                $items->where('price', '<=', $request->price_to);
            }

            if (! empty($request->level)) {
                $items->where('level_id', $request->level);
            }

            if ($request->rate) {
                $rate = $request->rate;
                $ids = \DB::SELECT("(SELECT  ref_id FROM `reviews` where type = 'group-class' group by ref_id HAVING  IFNull(round(sum(stars)/count(*),2),0) = $rate)");
                $ids_array = [];
                if ($ids) {
                    foreach ($ids as $id) {
                        $ids_array[] = $id->ref_id;
                    }
                }
                // return $ids_array;
                $items = $items->whereIn('group_classes.id', $ids_array);
                // $items = $item->whereRaw('select ')
                // $items = $items->whereHas('reviews', function($q) use($rate){
                //             $q->whereRaw('sum(starts)/count(*) = '.$rate.'');
                //         });
            }

            if (! empty($request->date_from)) {
                $items->leftjoin(\DB::raw('(SELECT class_id, (select min(class_date) from `group_class_dates` where class_id=gsd.class_id) date_from FROM `group_class_dates` gsd group by class_id) gcd_min'), 'gcd_min.class_id', 'group_classes.id');
                $items->where('gcd_min.date_from', '>=', $request->date_from);
            }

            if (! empty($request->date_to)) {
                $items->leftjoin(\DB::raw('(SELECT class_id, (select max(class_date) from `group_class_dates` where class_id=gsd.class_id) date_to FROM `group_class_dates` gsd group by class_id) gcd_max'), 'gcd_max.class_id', 'group_classes.id');
                $items->where('gcd_max.date_to', '<=', $request->date_to);
            }

            if (! empty($request->time_from)) {
                $items->leftjoin(\DB::raw('(SELECT class_id, (select min(TIME_FORMAT(class_date, \'%H:%i\')) from `group_class_dates` where class_id=gsd.class_id) time_from FROM `group_class_dates` gsd group by class_id) gct_min'), 'gct_min.class_id', 'group_classes.id');
                $items->where('gct_min.time_from', '>=', $request->time_from);
            }

            if (! empty($request->time_to)) {
                $items->leftjoin(\DB::raw('(SELECT class_id, (select max(TIME_FORMAT(class_date, \'%H:%i\')) from `group_class_dates` where class_id=gsd.class_id) time_to FROM `group_class_dates` gsd group by class_id) gct_max'), 'gct_max.class_id', 'group_classes.id');
                $items->where('gct_max.time_to', '<=', $request->time_to);
            }

            if (! empty($request->topic)) {
                $topic = $request->topic;
                $items->where(function ($query) use ($topic) {
                    $query->whereRaw(filterTextDB('group_class_langs.title').' like ?', ['%'.filterText($topic).'%']);
                    $query->orwhereRaw(filterTextDB('group_class_langs.about').' like ?', ['%'.filterText($topic).'%']);
                    $query->orwhereRaw(filterTextDB('group_class_langs.about').' like ?', ['%'.filterText($topic).'%']);
                    $query->orwhereRaw(filterTextDB('group_class_langs.headline').' like ?', ['%'.filterText($topic).'%']);
                    $query->orwhereRaw(filterTextDB('group_class_langs.information').' like ?', ['%'.filterText($topic).'%']);
                });
            }

            $items->distinct();

            /*return response([
                    'success' => true,
                    'message' => 'item-listed-successfully',
                    'result' => $items->toSql()
            ] , 200);*/

            // $items = $items->paginate($limit);

            $items = paginate($items, $limit);

            foreach ($items as $item) {
                $item->langs = $item->langs()->get();
                $item->dates = $item->dates()->get();
                // $item->reviews = $item->reviews()->get();
                $item->rating = $item->reviews()->avg('stars');
                $item->tutor;

                if ($item->tutor) {
                    $item->tutor->price = $item->tutor->hourlyPrices()?->first()?->price ?? 0;
                    $tutor_about = $item->tutor->abouts()->first();

                    if ($tutor_about) {
                        $item->tutor->language = $tutor_about->language()->first()->name ?? null;
                        $item->tutor->subject = $tutor_about->subject()->first()->name ?? null;
                    } else {
                        $item->tutor->language = null;
                        $item->tutor->subject = null;
                    }

                    $item->tutor->videos = $item->tutor->videos()->get();
                    $item->tutor->email = null;
                }

                /*$item->students = $item->students()->get();
                foreach($item->students as $student){
                    $outline->email=null;
                }*/

                /*$item->outlines = $item->outlines()->get();
                foreach($item->outlines as $outline){
                    $outline->outline;
                    $outline->dates = $outline->dates()->get();

                    foreach($outline->dates as $date) $date->tutor;
                }*/

                $item->hasExam = $item->exams->count() ? 1 : 0;
                // $item->isOrdered = Order::where(['user_id' => $user->id , 'ref_type' => 1, 'ref_id' => $item->id])->count() ? 1 : 0;

                $item->imageInfo;
                $item->attachment;
            }
        }

        $data = [
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items,
        ];
        // $responsejson = json_encode($data);
        // $gzipData = gzencode($responsejson, 9);

        // return response($gzipData)->withHeaders([
        //     'Access-Control-Allow-Origin' => '*',
        //     'Access-Control-Allow-Methods' => 'GET',
        //     'Content-type' => 'application/json; charset=utf-8',
        //     'Content-Length' => strlen($gzipData),
        //     'Content-Encoding' => 'gzip',
        // ]);

        return response($data, 200);
    }

    public function getTutorGroupClasses(Request $request)
    {
        $limit = setDataTablePerPageLimit($request->limit);
        $user = Auth::user();
        $tutor = Tutor::find($user->id);
        if (! $tutor) {
            return response([
                'success' => false,
                'message' => 'tutor-dose-not-exist',
                'msg-code' => '111',
            ], 200);
        }
        $items = GroupClassTutor::where('tutor_id', $tutor->id)
            ->whereHas('groupClass', function ($query) use ($request) {
                if (! empty($request->topic)) {
                    $topic = $request->topic;
                    $query->whereHas('langs', function ($subQuery) use ($topic) {
                        $subQuery->where('title', 'like ?', '%'.$topic.'%');
                    });
                }
            })
            ->with(['groupClass' => function ($query) {
                $query->select('id', 'name');
            }])
            ->select('status', 'group_class_id')
            ->orderBy('id', 'desc');

        $items = paginate($items, $limit);

        return response([
            'success' => true,
            'message' => 'items-retrieved-successfully',
            'result' => $items,
        ], 200);

    }

    public function tutorIndex(Request $request)
    {
        $user = Auth::user();

        $limit = setDataTablePerPageLimit($request->limit);

        // get all group classes
        $items = GroupClass::select('group_classes.*');
        // join group class langs
        $items->leftjoin('group_class_langs', 'group_class_langs.classid', 'group_classes.id');

        $items->where('tutor_id', $user->id)->orWhereNull('tutor_id');

        $items->distinct();

        $items = paginate($items, $limit);

        foreach ($items as $item) {
            $item->langs = $item->langs()->get();
            $item->dates = $item->dates()->get();
            $item->tutor;

            if ($item->tutor) {
                $item->tutor->email = null;
            }

            $item->tutor_status = GroupClassTutor::where('group_class_id', $item->id)->where('tutor_id', $user->id)->first() ? 1 : 0;

            $item->imageInfo;
            $item->attachment;
        }

        $data = [
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items,
        ];

        $responsejson = json_encode($data);
        $gzipData = gzencode($responsejson, 9);

        return response($gzipData)->withHeaders([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET',
            'Content-type' => 'application/json; charset=utf-8',
            'Content-Length' => strlen($gzipData),
            'Content-Encoding' => 'gzip',
        ]);

        return response($data, 200);
    }

    public function mostPopular(Request $request, $type)
    {
        return $this->listRequest($request);
    }

    public function index(Request $request, $id = 0)
    {
        return $this->listRequest($request, $id);
    }

    public function listRequest($request, $id = 0)
    {
        $user = Auth::user();

        // in update process
        if ($id != 0) {
            $items = GroupClass::find($id);
            if ($items) {
                $items->level;
                $items->category;
                if ($items->category) {
                    $items->category->langs = $items->category->langs()->get();
                }
                $items->langs = $items->langs()->get();
                $items->dates = $items->dates()->get();
                $items->reviews = $items->reviews()->get();
                if ($items->reviews) {
                    foreach ($items->reviews as $review) {
                        $review->user;
                        if ($review->user) {
                            $review->user->email = null;
                        }
                    }
                }
                $items->rating = $items->reviews()->avg('stars');
                $items->tutor;

                                
                $items->tutor->price =  $items->tutor->hourlyPrices()?->first()?->price ?? 0;


                $items->tutor->price = $items->tutor->hourlyPrices()?->first()?->price ?? 0;

                $tutor_about = $items->tutor->abouts()->first();
                if ($tutor_about) {
                    $items->tutor->language = $tutor_about->language()->first()->name ?? null;
                    $items->tutor->subject = $tutor_about->subject()->first()->name ?? null;
                } else {
                    $items->tutor->language = null;
                    $items->tutor->subject = null;
                }

                if ($items->tutor) {
                    $items->tutor->email = null;
                }
                /*$items->outlines = $items->outlines()->get();

                foreach($items->outlines as $outline){
                    $outline->outline;
                    $outline->dates = $outline->dates()->get();

                    foreach($outline->dates as $date) $date->tutor;
                }*/

                $items->imageInfo;
                $items->attachment;

                $items->appliedTutors;
                foreach ($items->appliedTutors as $appliedTutor) {
                    $appliedTutor->tutor;
                    $appliedTutor->tutor->price = $appliedTutor->tutor->hourlyPrices()?->first()?->price ?? 0;
                    $appliedTutor->tutor->rating = $appliedTutor->tutor->reviews()->avg('stars');

                    $appliedTutor->tutor->specialization = $appliedTutor->tutor->descriptions()->first()->specialization ?? '';

                }

                $suggestions = GroupClass::where('category_id', $items->category_id)->where('id', '<>', $items->id)->limit(6)->get();
                foreach ($suggestions as $suggestion) {
                    $suggestion->level;
                    $suggestion->category;
                    if ($suggestion->category) {
                        $suggestion->category->langs = $suggestion->category->langs()->get();
                    }
                    $suggestion->langs = $suggestion->langs()->get();
                    $suggestion->dates = $suggestion->dates()->get();
                    // $suggestion->reviews = $suggestion->reviews()->get();
                    $suggestion->rating = $suggestion->reviews()->avg('stars');
                    $suggestion->tutor;
                    if ($suggestion->tutor) {
                        $suggestion->tutor->email = null;
                    }

                    /*$item->students = $item->students()->get();
                    foreach($item->students as $student){
                        $outline->email=null;
                    }*/

                    /*$item->outlines = $item->outlines()->get();
                    foreach($item->outlines as $outline){
                        $outline->outline;
                        $outline->dates = $outline->dates()->get();

                        foreach($outline->dates as $date) $date->tutor;
                    }*/

                    $suggestion->imageInfo;
                }

                $items->suggestions = $suggestions;
            }
        } else {

            $limit = setDataTablePerPageLimit($request->limit);

            // get all group classes
            $items = GroupClass::with('level')->select('group_classes.*');
            // join group class langs
            $items->leftjoin('group_class_langs', 'group_class_langs.classid', 'group_classes.id');
            if (! empty($request->tutor_id)) {
                $items->where('tutor_id', $request->tutor_id);
            }

            if (! empty($request->category_id)) {
                $items->where('category_id', $request->category_id);
            }
            if (! empty($request->category)) {
                $items->where('category_id', $request->category);
            }

            if (! empty($request->price_from)) {
                $items->where('price', '>=', $request->price_from);
            }

            if (! empty($request->price_to)) {
                $items->where('price', '<=', $request->price_to);
            }

            if (! empty($request->level)) {
                $items->where('level_id', $request->level);
            }

            if ($request->rate) {
                $rate = $request->rate;
                $ids = \DB::SELECT("(SELECT  ref_id FROM `reviews` where type = 'group-class' group by ref_id HAVING  IFNull(round(sum(stars)/count(*),2),0) = $rate)");
                $ids_array = [];
                if ($ids) {
                    foreach ($ids as $id) {
                        $ids_array[] = $id->ref_id;
                    }
                }
                // return $ids_array;
                $items = $items->whereIn('group_classes.id', $ids_array);
                // $items = $item->whereRaw('select ')
                // $items = $items->whereHas('reviews', function($q) use($rate){
                //             $q->whereRaw('sum(starts)/count(*) = '.$rate.'');
                //         });
            }

            if (! empty($request->date_from)) {
                $items->leftjoin(\DB::raw('(SELECT class_id, (select min(class_date) from `group_class_dates` where class_id=gsd.class_id) date_from FROM `group_class_dates` gsd group by class_id) gcd_min'), 'gcd_min.class_id', 'group_classes.id');
                $items->where('gcd_min.date_from', '>=', $request->date_from);
            }

            if (! empty($request->date_to)) {
                $items->leftjoin(\DB::raw('(SELECT class_id, (select max(class_date) from `group_class_dates` where class_id=gsd.class_id) date_to FROM `group_class_dates` gsd group by class_id) gcd_max'), 'gcd_max.class_id', 'group_classes.id');
                $items->where('gcd_max.date_to', '<=', $request->date_to);
            }

            if (! empty($request->time_from)) {
                $items->leftjoin(\DB::raw('(SELECT class_id, (select min(TIME_FORMAT(class_date, \'%H:%i\')) from `group_class_dates` where class_id=gsd.class_id) time_from FROM `group_class_dates` gsd group by class_id) gct_min'), 'gct_min.class_id', 'group_classes.id');
                $items->where('gct_min.time_from', '>=', $request->time_from);
            }

            if (! empty($request->time_to)) {
                $items->leftjoin(\DB::raw('(SELECT class_id, (select max(TIME_FORMAT(class_date, \'%H:%i\')) from `group_class_dates` where class_id=gsd.class_id) time_to FROM `group_class_dates` gsd group by class_id) gct_max'), 'gct_max.class_id', 'group_classes.id');
                $items->where('gct_max.time_to', '<=', $request->time_to);
            }

            if (! empty($request->topic)) {
                $topic = $request->topic;
                $items->where(function ($query) use ($topic) {
                    $query->whereRaw(filterTextDB('group_class_langs.title').' like ?', ['%'.filterText($topic).'%']);
                    $query->orwhereRaw(filterTextDB('group_class_langs.about').' like ?', ['%'.filterText($topic).'%']);
                    $query->orwhereRaw(filterTextDB('group_class_langs.about').' like ?', ['%'.filterText($topic).'%']);
                    $query->orwhereRaw(filterTextDB('group_class_langs.headline').' like ?', ['%'.filterText($topic).'%']);
                    $query->orwhereRaw(filterTextDB('group_class_langs.information').' like ?', ['%'.filterText($topic).'%']);
                });
            }

            if (! empty($request->q)) {
                $items->whereRaw(
                    '('.filterTextDB('group_classes.name').' LIKE ?)',
                    ['%'.filterText($request->q).'%']
                );
            }

            $items->distinct();

            /*return response([
                    'success' => true,
                    'message' => 'item-listed-successfully',
                    'result' => $items->toSql()
            ] , 200);*/

            // $items = $items->paginate($limit);

            $items = paginate($items, $limit);

            foreach ($items as $item) {
                $item->langs = $item->langs()->get();
                $item->dates = $item->dates()->get();
                // $item->reviews = $item->reviews()->get();
                $item->rating = $item->reviews()->avg('stars');
                $item->tutor;

                if ($item->tutor) {
                    $item->tutor->email = null;
                }

                $item->appliedTutors;
                foreach ($item->appliedTutors as $appliedTutor) {
                    $appliedTutor->tutor;
                    if ($appliedTutor->tutor) {
                        $price = $appliedTutor->tutor?->hourlyPrices()?->first()?->price ?? 0;
                        $appliedTutor->tutor->setAttribute('price', $price);
                        $appliedTutor->tutor->rating = $appliedTutor->tutor->reviews()->avg('stars');

                        $appliedTutor->tutor->specialization =  $appliedTutor->tutor->descriptions()->first()->specialization ?? '';
                    }
                    

                }
                $item->tutor_status = GroupClassTutor::where('group_class_id', $item->id)->where('tutor_id', $user->id)->first() ? 1 : 0;

                $first_class_date = (new DateTime($item->dates()->first()->class_date))->format('Y-m-d');
                $now_date = date('Y-m-d');

                $item->canUnAssignedTutor = $item->tutor_id != null && $now_date < $first_class_date ? 1 : 0;

                /*$item->students = $item->students()->get();
                foreach($item->students as $student){
                    $outline->email=null;
                }*/

                /*$item->outlines = $item->outlines()->get();
                foreach($item->outlines as $outline){
                    $outline->outline;
                    $outline->dates = $outline->dates()->get();

                    foreach($outline->dates as $date) $date->tutor;
                }*/

                $item->imageInfo;
                $item->attachment;
                // if($class->level?->level_number > 1){
                //     if($class->exams?->count() <= 0){
                //         return false;
                //     }
                // }
                $item->level_number = $item->level?->level_number;
                if($item->level_number > 1){
                    $item->have_exams = $item->exams?->count() > 0 ? 1 : 0;
                }
            }
        }

        $data = [
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items,
        ];

        $responsejson = json_encode($data);
        $gzipData = gzencode($responsejson, 9);

        return response($gzipData)->withHeaders([
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET',
            'Content-type' => 'application/json; charset=utf-8',
            'Content-Length' => strlen($gzipData),
            'Content-Encoding' => 'gzip',
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

        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'string',
                    Rule::unique('group_classes')->ignore($id)->whereNull('deleted_at'),
                ],
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'msg-code' => 'validation-error',
                ], 200);

            }

            $data = $request->only(['name', 'category_id', 'price', 'level_id', 'classes', 'class_length', 'total_classes_length', 'frequency_id', 'min_size', 'metadata', 'embed', 'image', 'attachment', 'status', 'publish', 'publish_date', 'start_month', 'sessions_hour']); // 'tutor_id'
            $user = Auth::user();
            $data['user_id'] = $user->id;
            $data['ipaddress'] = $request->ip();

            /*if($id>0){
                GroupClassDate::where('class_id',$id)->delete();
                Conference::where('ref_id',$id)->where('ref_type',0)->where('order_id',0)->delete();
            }*/

            // $data['sessions_hour'] = DateTime::createFromFormat('h:i A', $data['sessions_hour'])->format('H:i:s');
            $sessions_hour = $data['sessions_hour'];
            if (DateTime::createFromFormat('H:i:s', $sessions_hour) !== false) {
                $data['sessions_hour'] = $sessions_hour;
            } else {
                $dateTime = DateTime::createFromFormat('h:i A', $sessions_hour);
                if ($dateTime !== false) {
                    $data['sessions_hour'] = $dateTime->format('H:i:s');
                } else {
                    error_log('Invalid time input: '.$sessions_hour);
                    throw new \InvalidArgumentException("Invalid time format. Expected 'h:i A' or 'H:i:s'.");
                }
            }

            $group_class_dates = [];
            foreach ($request->dates as $date) {

                $originalDate = new DateTime($date['class_date']);
                $newMonth = Carbon::parse($request->start_month)->month;

                // Change the month
                $originalDate->setDate($originalDate->format('Y'), $newMonth, $originalDate->format('d'));

                $group_class_dates[]['class_date'] = $originalDate->format('Y-m-d').' '.$data['sessions_hour'];
            }

            if ($id == 0 && empty($group_class_dates)) {
                return response([
                    'success' => false,
                    'message' => 'dates-is-empty',
                    'msg-code' => '111',
                ], 200);
            }

            // if($id==0) {
            //     $tutor= Tutor::find($request->tutor_id);
            //     if(!$tutor) return response([
            //             'success' => false,
            //             'message' => 'tutor-dose-not-exist',
            //             'msg-code' => '222'
            //     ] , 400);
            // }

            if ($id == 0 && ! empty($group_class_dates) && count($group_class_dates) > 0) {
                $unique_dates = [];
                foreach ($group_class_dates as $date) {
                    $now = new DateTime('now');
                    $start_date = $date['class_date'];
                    $end_date = date('Y-m-d H:i:s', strtotime($start_date.' +15 minutes'));
                    $book_start = new DateTime($start_date);
                    $book_end = new DateTime($end_date);
                    if ($book_start <= $now) {
                        return response([
                            'success' => false,
                            'message' => 'book-date-is-old',
                            'msg-code' => '555',
                        ], 200);
                    }

                    $_date = date('Y-m-d', strtotime($start_date));
                    if (isset($unique_dates[$_date])) {
                        return response([
                            'success' => false,
                            'message' => 'please-enter-different-dates',
                            'msg-code' => '666',
                        ], 200);
                    } else {
                        $unique_dates[$_date] = $_date;
                    }
                    // $checkConflictTutorDate = Conference::where('tutor_id',$tutor->id)->whereRaw('(start_time=? or end_time=? or (? > start_time and ? < end_time) or (? > start_time and ? < end_time))',[$start_date , $end_date, $start_date , $start_date, $end_date , $end_date])->first();
                    // if($checkConflictTutorDate)
                    //     return response([
                    //             'success' => false,
                    //             'message' => 'tutor-date-conflict',
                    //             'msg-code' => '666'
                    //     ] , 200);
                }
                $original_dates = array_values($unique_dates);
                sort($unique_dates);
                if ($original_dates != $unique_dates) {
                    return response([
                        'success' => false,
                        'message' => 'please-enter-sorted-dates',
                        'msg-code' => '777',
                    ], 200);
                }
            }

            if ($id > 0) {
                $item = GroupClass::find($id);
                if (! $item) {
                    return response([
                        'success' => false,
                        'message' => 'item-dose-not-exist',
                        'msg-code' => '111',
                    ], 200);
                }
                $data['tutor_id'] = $item->start_month == $request->start_month ? $item->tutor_id : null;

                if ($item->start_month == $request->start_month) {
                    $data['tutor_id'] = $item->tutor_id;
                } else {
                    $data['tutor_id'] = null;
                    GroupClassTutor::where('group_class_id', $item->id)->delete();
                }

                $item->update($data);
            } else {
                $item = GroupClass::create($data);
            }

            $this->setGroupClassLangs($item, $request->langs);
            // $this->setGroupClassOutlines($item,$request->outlines);

            $this->setGroupClassDates($item, $group_class_dates);
            $this->setGroupClassConferences($item);

            DB::commit();

            return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item,
            ], 200);

        } catch (\Throwable $th) {
            DB::rollBack();

            return response([
                'success' => false,
                'message' => $th->getMessage(),
                'msg-code' => '444',
                'error' => $th->getTraceAsString(),
            ], 200);
        }
    }

    public function setGroupClassConferences($item)
    {
        $groupClass = GroupClass::find($item->id);
        Conference::where(['ref_type' => 1, 'ref_id' => $groupClass->id])->delete();
        $dates = $groupClass->dates()->get();
        // dd($dates);
        foreach ($dates as $date) {
            $conference = new Conference;
            $conference->student_id = 0;
            $conference->tutor_id = $groupClass->tutor_id;

            $conference->ref_id = $groupClass->id;
            $conference->ref_type = 1;
            $conference->order_id = 0;

            $conference->title = $groupClass->name;

            $conference->start_time = $date->class_date;
            $conference->end_time = date('Y-m-d H:i:s', strtotime($conference->start_time.' +40 minutes'));

            $start_time = explode(' ', $conference->start_time);
            $end_time = explode(' ', $conference->end_time);
            // echo $end_date_time;exit;
            // echo date("H:iA", strtotime($date_time[1]));exit;

            $conference->date = $start_time[0];
            $conference->start_time = date('h:iA', strtotime($start_time[1]));
            $conference->end_time = date('h:iA', strtotime($end_time[1]));

            $conference->record = 3;
            $conference->timezone = 35;

            $conference->type = 'zoom'; // braincert
            $conference->status = 0;
            $conference->save();

            $postValues = [
                'title' => $conference->title,
                'timezone' => 35,
                'start_time' => $conference->start_time,
                'end_time' => $conference->end_time,
                'date' => $conference->date,
                'record' => 3,
            ];

            // $braincert = new BraincertController;
            // $conference->response = $braincert->conferenceCreate($postValues);
            $zoom = new ZoomController;
            // $conference->response = $zoom->createMeeting($postValues);
            $result = $zoom->createMeeting($postValues);

            $conference->response = json_encode($result);
            // $conference->notes = json_encode($postValues);
            $conference->save();
        }
    }

    public function setGroupClassLangs($item, $langs)
    {
        // GroupClassLang::where('classid',$item->id)->delete();
        if (! empty($langs) && count($langs) > 0) {
            foreach ($langs as $langArray) {
                $langJson = json_encode($langArray);
                $lang = json_decode($langJson);
                $checkLang = GroupClassLang::where('classid', $item->id)->where('language_id', $lang->language_id)->first();
                $langData = [
                    'classid' => $item->id,
                    'language_id' => $lang->language_id,
                    'slug' => isset($lang->slug) ? $lang->slug : '',
                    'title' => isset($lang->title) ? $lang->title : '',
                    'about' => isset($lang->about) ? $lang->about : '',
                    'headline' => isset($lang->headline) ? $lang->headline : '',
                    'information' => isset($lang->information) ? $lang->information : '',
                    'user_id' => $item->user_id,
                    'ipaddress' => $item->ipaddress,
                ];

                if (! $checkLang) {
                    GroupClassLang::create($langData);
                } else {
                    GroupClassLang::where('classid', $item->id)->where('language_id', $lang->language_id)->update($langData);
                }
            }
        }
    }

    public function setGroupClassOutlines($item, $outlines)
    {
        // GroupClassOutline::where('class_id',$item->id)->delete();
        if (! empty($outlines) && count($outlines) > 0) {
            foreach ($outlines as $outline) {
                $checkOutline = GroupClassOutline::where('class_id', $item->id)->where('outline_id', $outline['outline_id'])->first();
                $gcoData = [
                    'class_id' => $item->id,
                    'outline_id' => $outline['outline_id'],
                    'user_id' => $item->user_id,
                    'ipaddress' => $item->ipaddress,
                ];

                $gco = null;

                if (! $checkOutline) {
                    $gco = GroupClassOutline::create($gcoData);
                } else {
                    GroupClassOutline::where('class_id', $item->id)->where('outline_id', $outline['outline_id'])->update($gcoData);
                    $gco = GroupClassOutline::where('class_id', $item->id)->where('outline_id', $outline['outline_id'])->first();
                }

                if ($gco) {
                    $this->setGroupClassDates($gco, $outline['dates']);
                }
            }
        }
    }

    public function setGroupClassDates($item, $dates)
    {
        // var_dump($item);exit;
        GroupClassDate::where('class_id', $item->id)->delete();
        if (! empty($dates) && count($dates) > 0) {
            foreach ($dates as $date) {
                GroupClassDate::create([
                    // 'gco_id'=>$item->id,
                    'class_id' => $item->id,
                    // 'outline_id'=>$item->outline_id,
                    // 'tutor_id'=>$date['tutor_id'],
                    'class_date' => $date['class_date'],
                    'user_id' => $item->user_id,
                    'ipaddress' => $item->ipaddress,
                ]);
            }
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $item = GroupClass::find($id);
        if (! $item) {
            return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111',
            ], 200);
        }

        $item->level;
        $item->category;
        if ($item->category) {
            $item->category->langs = $item->category->langs()->get();
        }

        $item->langs = $item->langsAll()->get();

        $item->dates = $item->dates()->get();
        $item->reviews = $item->reviews()->get();
        if ($item->reviews) {
            foreach ($item->reviews as $review) {
                $review->user;
                if ($review->user) {
                    $review->user->email = null;
                }
            }
        }
        $item->rating = $item->reviews()->avg('stars');
        $item->tutor;

        if ($item->tutor) {
            $item->tutor->email = null;
            $item->tutor->videos = $item->tutor->videos()->get();
        }

        /*$item->students = $item->students()->get();
        foreach($item->students as $student){
            $outline->email=null;
        }*/

        /*$item->outlines = $item->outlines()->get();
        foreach($item->outlines as $outline){
            $outline->outline;
            $outline->dates = $outline->dates()->get();

            foreach($outline->dates as $date) $date->tutor;
        }*/

        $item->imageInfo;
        $item->attachment;
        $item->appliedTutors;
        foreach ($item->appliedTutors as $appliedTutor) {
            $appliedTutor->tutor;
            if($appliedTutor->tutor) {
                $appliedTutor->tutor->price = $appliedTutor->tutor->hourlyPrices()?->first()?->price ?? 0;
                $appliedTutor->tutor->rating = $appliedTutor->tutor->reviews()->avg('stars');

                $appliedTutor->tutor->specialization = $appliedTutor->tutor->descriptions()->first()->specialization ?? '';
            }
        }
        $suggestions = GroupClass::where('category_id', $item->category_id)->where('id', '<>', $item->id)->limit(6)->get();
        foreach ($suggestions as $suggestion) {
            $suggestion->level;
            $suggestion->category;
            if ($suggestion->category) {
                $suggestion->category->langs = $suggestion->category->langs()->get();
            }
            $suggestion->langs = $suggestion->langs()->get();
            $suggestion->dates = $suggestion->dates()->get();
            // $suggestion->reviews = $suggestion->reviews()->get();
            $suggestion->rating = $suggestion->reviews()->avg('stars');
            $suggestion->tutor;
            if ($suggestion->tutor) {
                $suggestion->tutor->email = null;
            }

            /*$item->students = $item->students()->get();
            foreach($item->students as $student){
                $outline->email=null;
            }*/

            /*$item->outlines = $item->outlines()->get();
            foreach($item->outlines as $outline){
                $outline->outline;
                $outline->dates = $outline->dates()->get();

                foreach($outline->dates as $date) $date->tutor;
            }*/

            $suggestion->imageInfo;
        }

        $item->suggestions = $suggestions;

        return response([
            'success' => true,
            'message' => 'item-showen-successfully',
            'result' => $item,
        ], 200);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $item = GroupClass::find($id);
        if (! $item) {
            return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111',
            ], 200);
        }

        // إرسال إشعارات للطلاب والمدرّس قبل حذف الكلاس
        $studentIds = GroupClassStudent::where('class_id', $item->id)->pluck('student_id');
        if($studentIds->isNotEmpty()) {
            foreach($studentIds as $studentId) {
                $student = User::find($studentId);
                if($student && $student->fcm) {
                    $info = [
                        'type' => 'class_cancelled',
                        'group_class_id' => $item->id
                    ];
                    sendFCMNotification(
                        'Class Cancelled', 
                        'The group class "' . $item->name . '" has been cancelled', 
                        $student->fcm, 
                        $info
                    );
                }
            }
        }
        
        // إشعار للمدرّس
        if($item->tutor_id) {
            $tutor = User::find($item->tutor_id);
            if($tutor && $tutor->fcm) {
                $info = [
                    'type' => 'class_cancelled',
                    'group_class_id' => $item->id
                ];
                sendFCMNotification(
                    'Class Cancelled', 
                    'The group class "' . $item->name . '" has been cancelled', 
                    $tutor->fcm, 
                    $info
                );
            }
        }

        $item->delete();

        return response([
            'success' => true,
            'message' => 'item-deleted-successfully',
        ], 200);
    }

    public function groupClassDetails(Request $request, $id)
    {
        $group_class = GroupClass::find($id);

        if (! $group_class) {
            return response([
                'success' => false,
                'message' => 'group-class-dose-not-exist',
                'msg-code' => '111',
            ], 200);
        }

        $conferences = Conference::where(['ref_type' => 1, 'ref_id' => $group_class->id])->get();
        $studentIds = GroupClassStudent::where('class_id', $group_class->id)->pluck('student_id');
        $students = User::select('id', 'name')->whereIn('id', $studentIds)->get();
        foreach ($conferences as $key => $conference) {
            $conference->is_taken = ConferenceAttendance::where('conference_id', $conference->id)->exists();
            $conference['students'] = $students->map(function ($student) use ($conference) {
                $student_attendance = ConferenceAttendance::where([
                    'conference_id' => $conference->id,
                    'user_id' => $student->id,
                ])->first();

                return array_merge($student->toArray(), [
                    'status' => $student_attendance ? $student_attendance->status : 0,
                ]);
            });
        }

        return response([
            'success' => true,
            'message' => 'group-class-details-retrieved-successfully',
            'data' => [
                'group_class' => $group_class,
                'conferences' => $conferences,
            ],
        ] , 200);
    }
}
