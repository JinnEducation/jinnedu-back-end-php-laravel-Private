<?php

namespace App\Http\Controllers;

use App\Models\Tutor;
use App\Models\User;
use App\Models\UserFavorite;
use App\Models\GroupClass;
use App\Models\GroupClassStudent;
use Illuminate\Http\Request;
use App\Models\WeekDay;
use App\Models\Order;
use App\Models\Role;
use DateTime;

use App\Http\Requests\Auth\RegisterRequest;

use Bouncer;
use Silber\Bouncer\Bouncer as BouncerBouncer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\UserAvailability;

class UserController extends Controller
{

    public function test(){
        return response(Tutor::all());
    }
    public function tutorsSearch(Request $request)
    {
        $limit = setDataTablePerPageLimit($request->limit);
        $items = Tutor::select('tutors.name', 'tutors.id', \DB::raw("(select avg(stars) from tutor_reviews where tutor_reviews.tutor_id=tutors.id and deleted_at is NULL) as rating"), 'tutors.type', 'user_hourly_prices.price', 'tutors.avatar', 'tutors.online', \DB::raw("CONCAT(user_abouts.first_name,' ',user_abouts.last_name) full_name"));

        $items->leftjoin('user_abouts', 'user_abouts.user_id', 'tutors.id');
        $items->leftjoin('user_descriptions', 'user_descriptions.user_id', 'tutors.id');
        $items->leftjoin('user_hourly_prices', 'user_hourly_prices.user_id', 'tutors.id');
        $items->leftjoin('user_availabilities', 'user_availabilities.user_id', 'tutors.id');
        $items->leftjoin('user_educations', 'user_educations.user_id', 'tutors.id');
        $items->leftjoin('user_languages', 'user_languages.user_id', 'tutors.id');
        //$items->leftjoin('conferences','conferences.tutor_id','tutors.id');

        //$items->whereRaw('user_abouts.user_id is not null and user_descriptions.user_id is not null and user_hourly_prices.user_id is not null', []);

        if(!empty($request->subject)) {
            $items->where('user_abouts.subject_id', $request->subject);
        }

        if(!empty($request->specialization)) {
            $items->whereRaw('(user_descriptions.specialization_id=? or user_educations.specialization_id=?)', [$request->specialization,$request->specialization]);
        }

        if(!empty($request->country)) {
            $items->where('user_abouts.country_id', $request->country);
        }

        if(!empty($request->country)) {
            $items->where('user_abouts.country_id', $request->country);
        }

        if(!empty($request->native_language)) {
            $items->where('user_abouts.language_id', $request->native_language);
        }

        if(!empty($request->language)) {
            $items->whereRaw('(user_languages.language_id=? or user_abouts.language_id=?)', [$request->language,$request->language]);
        }

        if(!empty($request->hourly_price_from)) {
            $items->where('user_hourly_prices.price', '>=', $request->hourly_price_from);
        }

        if(!empty($request->hourly_price_to)) {
            $items->where('user_hourly_prices.price', '<=', $request->hourly_price_to);
        }

        if(!empty($request->available_hour)) {
            $items->whereRaw("TIME_FORMAT(user_availabilities.hour_from, '%H:%i')<=? and TIME_FORMAT(user_availabilities.hour_to, '%H:%i')>=?", [$request->available_hour,$request->available_hour]);
        }

        if(!empty($request->available_hour_from) && !empty($request->available_hour_to)) {

            // $items->whereRaw("TIME_FORMAT(user_availabilities.hour_from, '%H:%i')<=?", [$request->available_hour_from]);
            $available_hour_from = explode(',', $request->available_hour_from);
            $available_hour_to = explode(',', $request->available_hour_to);
            if(count($available_hour_from) == count($available_hour_to)) {
                $items->where(function ($query) use ($available_hour_from, $available_hour_to) {
                    foreach($available_hour_from as $key => $from) {
                        $to = $available_hour_to[$key];
                        $queryText = "( (TIME_FORMAT(user_availabilities.hour_from, '%H:%i') <= ? && TIME_FORMAT(user_availabilities.hour_to, '%H:%i') > ?) or (TIME_FORMAT(user_availabilities.hour_from, '%H:%i') < ? && TIME_FORMAT(user_availabilities.hour_to, '%H:%i') >= ?) )";
                        $queryData = [$from,$from,$to,$to];
                        if($key == 0) {
                            $query->whereRaw($queryText, $queryData);
                        } else {
                            $query->orwhereRaw($queryText, $queryData);
                        }
                    }
                });
            }
        }

        /*if(!empty($request->available_hour_to)){
            $available_hour_to = explode(',',$request->available_hour_to);
               $items->where(function ($query)  use($available_hour_to){
                        foreach($available_hour_to as $key=>$to){
                            if($key==0){
                                $query->whereRaw("TIME_FORMAT(user_availabilities.hour_to, '%H:%i') >= ?",[$to]);
                            }else{
                                $query->orwhereRaw("TIME_FORMAT(user_availabilities.hour_to, '%H:%i') >= ?",[$to]);
                            }
                        }
            });
            // $items->whereRaw("TIME_FORMAT(user_availabilities.hour_to, '%H:%i') >=?", [$request->available_hour_to]);
        }*/

        if(!empty($request->available_date)) {
            $datetime = DateTime::createFromFormat('Y-m-d', $request->available_date);
            //dd($datetime->format('l'));
            $day = WeekDay::where('name', strtolower($datetime->format('l')))->first();
            $period = 0;
            if($day) {
                $allowDateCondTxt = " (user_availabilities.day_id=?) and (1=0 ";
                $allowDateCondData = [$day->id];
                $start_date = $request->available_date . ' 00:00:00';
                $book_start_date = new DateTime($start_date);
                $end_of_day = date('Y-m-d H:i:s', strtotime($request->available_date . ' 23:59:59'));
                $book_end_of_day = new DateTime($end_of_day);
                do {
                    $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $period . ' minutes'));
                    $book_end_date = new DateTime($end_date);
                    $allowDateCondTxt .= " or (user_availabilities.hour_from<=? and user_availabilities.hour_to>=? and tutors.id not in (select tutor_id from conferences where (start_date_time=? or end_date_time=? or (? > start_date_time and ? < end_date_time) or (? > start_date_time and ? < end_date_time)))) ";
                    $allowDateCondData = array_merge($allowDateCondData, [$book_start_date->format('H:i'),$book_end_date->format('H:i'), $start_date , $end_date, $start_date , $start_date, $end_date , $end_date]);
                    $period += 30;
                    $start_date = date('Y-m-d H:i:s', strtotime($request->available_date . ' 00:00:00 +' . $period . ' minutes'));
                    $book_start_date = new DateTime($start_date);
                } while($book_start_date < $book_end_of_day);
                $start_date = date('Y-m-d H:i:s', strtotime($end_of_day . ' -30 minutes'));
                $end_date = $end_of_day;
                $allowDateCondTxt .= " or (user_availabilities.hour_from<=? and user_availabilities.hour_to>=? and tutors.id not in (select tutor_id from conferences where (start_date_time=? or end_date_time=? or (? > start_date_time and ? < end_date_time) or (? > start_date_time and ? < end_date_time)))) ";
                $allowDateCondData = array_merge($allowDateCondData, [$book_start_date->format('H:i'),$book_end_date->format('H:i'), $start_date , $end_date, $start_date , $start_date, $end_date , $end_date]);
                $allowDateCondTxt .= ' ) ';
                $items->whereRaw($allowDateCondTxt, $allowDateCondData);
            //$items->whereRaw("(tutors.id not in (select tutor_id from conferences where (start_date_time=? or end_date_time=? or (? > start_date_time and ? < end_date_time) or (? > start_date_time and ? < end_date_time))))", [$start_date , $end_date, $start_date , $start_date, $end_date , $end_date]);
            } else {
                $items->whereRaw("1=0", []);
            }

        }

        if(!empty($request->full_name)) {
            $items->whereRaw(filterTextDB("CONCAT(user_abouts.first_name,' ',user_abouts.last_name)") . ' like ?', ['%' . filterText($request->full_name) . '%']);
        }

        /*if(!empty($request->specialization)){
            $items->whereRaw(filterTextDB("user_educations.specialization").' like ?',['%'.filterText($request->specialization).'%']);
        }*/


        switch($request->sort_by) {
            case 'full_name_asc': $items->orderBy('full_name', 'ASC');
                break;
            case 'full_name_desc':$items->orderBy('full_name', 'DESC');
                break;
            case 'price_asc': $items->orderBy('price', 'ASC');
                break;
            case 'price_desc':$items->orderBy('price', 'DESC');
                break;
            case 'rating_asc': $items->orderBy('rating', 'ASC');
                break;
            case 'rating_desc':$items->orderBy('rating', 'DESC');
                break;
            default: $items->orderBy('id', 'ASC');
                break;
        }

        $items->distinct();

        /*return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items->toSql()
        ] , 200);*/

        // $items = $items->paginate($limit);
        $items = paginate($items, $limit);

        foreach($items as $item) {
            $item->videos =  $item->videos()->get();
            $item->descriptions =  $item->descriptions()->get();
            $item->rating = $item->reviews()->avg('stars');
            //$item->reviews = $item->reviews()->get();
            //======================================================
            $item->abouts =  $item->abouts()->get();
            if($item->abouts) {
                foreach($item->abouts as $about) {
                    $about->country;
                    $about->level;
                    $about->language;
                    $about->subject;
                    $about->experience;
                    $about->situation;
                }
            }
            //======================================================

            $tutorStudent = GroupClassStudent::join('group_classes', 'group_classes.id', '=', 'group_class_students.class_id')
                ->where('group_classes.tutor_id', $item->id)
                ->select(\DB::raw('COUNT(DISTINCT group_class_students.student_id) as unique_student_count'))
                ->first()->unique_student_count;
                    
            $groupClassCount = GroupClass::where('tutor_id', $item->id)->count();

            $item->students = $tutorStudent;
            $item->lessons = $groupClassCount;
            
            // Fetch user availabilities
            $availabilities = UserAvailability::where('user_id', $item->id)->get();
        
            $days = WeekDay::pluck('id', 'name')->toArray();
        
            $timeSlots = [
                'morning' => ['start' => '06:00', 'end' => '12:00'],
                'afternoon' => ['start' => '12:00', 'end' => '18:00'],
                'evening' => ['start' => '18:00', 'end' => '22:00'],
                'night' => ['start' => '22:00', 'end' => '06:00'],
            ];
        
            $availabilityByDay = [];
        
            foreach ($days as $dayName => $dayId) {
                $availabilityByDay[$dayName] = [
                    'morning' => false,
                    'afternoon' => false,
                    'evening' => false,
                    'night' => false,
                ];
        
                // Filter availabilities for the current day
                $dayAvailabilities = $availabilities->where('day_id', $dayId);
        
                // Check each time slot
                foreach ($dayAvailabilities as $availability) {
                    $from = Carbon::parse($availability->hour_from);
                    $to = Carbon::parse($availability->hour_to);
        
                    foreach ($timeSlots as $slot => $times) {
                        $slotStart = Carbon::parse($times['start']);
                        $slotEnd = Carbon::parse($times['end']);
        
                        // Handle night slot wrapping around midnight
                        if ($slot === 'night') {
                            if (
                                ($from->between($slotStart, $slotEnd->addDay()) || $to->between($slotStart, $slotEnd->addDay())) ||
                                ($from->lte($slotStart) && $to->gte($slotEnd->addDay()))
                            ) {
                                $availabilityByDay[$dayName][$slot] = true;
                            }
                        } else {
                            if (
                                ($from->between($slotStart, $slotEnd) || $to->between($slotStart, $slotEnd)) ||
                                ($from->lte($slotStart) && $to->gte($slotEnd))
                            ) {
                                $availabilityByDay[$dayName][$slot] = true;
                            }
                        }
                    }
                }
            }
        

            $item->calendar = $availabilityByDay;

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

    public function tutorShow($id)
    {
        $user = Tutor::select('tutors.name', 'tutors.id', 'tutors.type', 'tutors.avatar', 'tutors.online')->where('id', $id)->first();
        if(!$user) {
            return response([
                    'success' => false,
                    'message' => 'tutor-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }
        //======================================================
        $user->about =  $user->abouts()->first();
        if($user->about) {
            $user->about->country;
            $user->about->level;
            $user->about->language;
            $user->about->subject;
            $user->about->experience;
            $user->about->situation;
        }
        //======================================================
        $user->availabilities =  $user->availabilities()->get();
        if($user->availabilities) {
            foreach($user->availabilities as $availability) {
                $availability->timezone;
                $availability->day;
            }
            $dates = [];
            for($day = 1;$day <= 7;$day++) {
                foreach($user->availabilities as $availability) {
                    if($availability->day->id == $day) {
                        $dates[$availability->day->name] = [];
                        $period = 0;
                        $start_hour = date('Y-m-d') . ' ' . $availability->hour_from . ':00';
                        $book_start_hour = new DateTime($start_hour);
                        $end_hour = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' ' . $availability->hour_to . ':00'));
                        $book_end_hour = new DateTime($end_hour);
                        do {
                            $dates[$availability->day->name][] = $book_start_hour->format('H:i');
                            $period += 30;
                            $start_hour = date('Y-m-d H:i:s', strtotime(date('Y-m-d') . ' ' . $availability->hour_from . ':00 +' . $period . ' minutes'));
                            $book_start_hour = new DateTime($start_hour);
                        } while($book_start_hour < $book_end_hour);
                        $dates[$availability->day->name][] = $book_end_hour->format('H:i');
                    }
                }
            }
            $user->dates = $dates;
        }
        //======================================================
        $user->certifications =  $user->certifications()->get();
        if($user->certifications) {
            foreach($user->certifications as $certification) {
                $certification->subject;
            }
        }
        //======================================================
        $user->descriptions =  $user->descriptions()->first();
        if($user->descriptions) {
            $user->descriptions->specialization;
        }
        //======================================================
        $user->rating = $user->reviews()->avg('stars');
        $user->reviews =  $user->reviews()->get();
        if($user->reviews) {
            foreach($user->reviews as $review) {
                $review->user;
                if($review->user) {
                    $review->user->email = null;
                }
            }
        }
        //======================================================
        $user->educations =  $user->educations()->get();
        if($user->educations) {
            foreach($user->educations as $education) {
                $education->degreeType;
                $education->specialization;
            }
        }
        //======================================================
        $user->hourlyPrices =  $user->hourlyPrices()->get();
        //======================================================
        $user->languages =  $user->languages()->get();
        if($user->languages) {
            foreach($user->languages as $language) {
                $language->language;
                $language->level;
            }
        }
        //======================================================
        $user->video =  $user->videos()->first();
        //======================================================

        $tutorStudent = GroupClassStudent::join('group_classes', 'group_classes.id', '=', 'group_class_students.class_id')
                ->where('group_classes.tutor_id', $id)
                ->select(\DB::raw('COUNT(DISTINCT group_class_students.student_id) as unique_student_count'))
                ->first()->unique_student_count;
                
        $groupClassCount = GroupClass::where('tutor_id', $id)->count();

        $user->students = $tutorStudent;
        $user->lessons = $groupClassCount;
        
        //======================================================

        $suggestions = Tutor::select('tutors.name', 'tutors.id', \DB::raw("(select avg(stars) from tutor_reviews where tutor_reviews.tutor_id=tutors.id and deleted_at is NULL) as rating"), 'tutors.type', 'user_hourly_prices.price', 'tutors.avatar', 'tutors.online', \DB::raw("CONCAT(user_abouts.first_name,' ',user_abouts.last_name) full_name"))
                                ->leftjoin('user_abouts', 'user_abouts.user_id', 'tutors.id')
                                ->leftjoin('user_descriptions', 'user_descriptions.user_id', 'tutors.id')
                                ->leftjoin('user_hourly_prices', 'user_hourly_prices.user_id', 'tutors.id')
                                ->leftjoin('user_availabilities', 'user_availabilities.user_id', 'tutors.id')
                                ->leftjoin('user_educations', 'user_educations.user_id', 'tutors.id')
                                ->leftjoin('user_languages', 'user_languages.user_id', 'tutors.id')
                                ->where('user_abouts.subject_id', $user->about->subject_id)->where('tutors.id', '<>', $user->id)->distinct()->limit(6)->get();


        foreach($suggestions as $suggestion) {
            $suggestion->videos =  $suggestion->videos()->get();
            $suggestion->descriptions =  $suggestion->descriptions()->get();
            $suggestion->rating = $suggestion->reviews()->avg('stars');
            //$suggestion->reviews = $suggestion->reviews()->get();
            //======================================================
            $suggestion->abouts =  $suggestion->abouts()->get();
            if($suggestion->abouts) {
                foreach($suggestion->abouts as $about) {
                    $about->country;
                    $about->level;
                    $about->language;
                    $about->subject;
                    $about->experience;
                    $about->situation;
                }
            }
            //======================================================
        }

        $user->suggestions = $suggestions;

        return response([
            'success' => true,
            'message' => 'Tutor retreived successfully',
            'result' => $user
        ]);
    }

    public function index(Request $request)
    {
        $limit = setDataTablePerPageLimit($request->limit);
        $items = User::select('users.*')
            ->distinct()
            ->leftJoin('assigned_roles', 'users.id', '=', 'assigned_roles.entity_id')
            ->leftJoin('roles', 'roles.id', '=', 'assigned_roles.role_id')
            ->where('roles.name', '!=', 'superadmin');

        if($request->type != null) {
            $items->where('users.type', $request->type);
        }

        if(!empty($request->tutor_id)) {
            $items->where('users.id', $request->tutor_id);
        }

        if(!empty($request->q)) {
            $items->whereRaw(
                '(' . filterTextDB('users.name') . ' LIKE ? OR users.email LIKE ?)', 
                ['%' . filterText($request->q) . '%', '%' . filterText($request->q) . '%']
            );
            $items->distinct();
        }

        // $items = $items->paginate($limit);
        $items = paginate($items, $limit);

        foreach($items as $key=>$item) {
            $item->roles = Bouncer::role()->get();
            //$user = User::find($item->id);
            foreach($item->roles as $role) {
                if(Bouncer::is($item)->a($role->name)) {
                    $role = $role->checked = true;
                } else {
                    $role->checked = false;
                }
            }
        }

        return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items
        ], 200);
    }

    public function update(RegisterRequest $request, $id)
    {
        return $this->storeUpdate($request, $id);
    }

    public function store(Request $request) //RegisterRequest $request
    {
        return $this->storeUpdate($request);
    }

    public function storeUpdate($request, $id = 0)
    {
                
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|string|unique:users,email,'. $id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 200);
        }

        $data = $request->only(['name','email']);//,'password'
        $item = null;

        if($id > 0) {
            $item = User::find($id);
            if(!$item) {
                return response([
                        'success' => false,
                        'message' => 'item-dose-not-exist',
                        'msg-code' => '111'
                ], 200);
            }
            //var_dump($data);
            if(!empty($request->password)) {
                $data['password'] = bcrypt($request->password);
            }
            $item->update($data);

        } else {
            if(empty($request->password)) {
                $data['password'] = bcrypt(create_random_code(8));
            } else {
                $data['password'] = bcrypt($request->password);
            }

            $item = User::factory()->create($data);
            //$user->hasVerifiedEmail();
        }

        $item->save();
        //dd($request->avatar);
        if(!empty($request->avatar)) {
            $item->avatar = uploadFile($request->avatar, ['jpg','jpeg','png'], 'users');
            $item->save();
        }

        $roles = json_decode($request->roles);
        //return response(['success' => true,'message' => $roles,'msg-code' => '000'] , 200);
        // $item->type = 0;
        // $item->save();
        if(isset($roles) && count($roles) > 0) {
            foreach($roles as $role) {
                $roleInfo = Bouncer::role()->find($role->id);
                if($roleInfo) {
                    if($role->allow && Bouncer::is($item)->notA($roleInfo->name)) {
                        switch($roleInfo->name) {
                            case 'student':  $item->type = 1;
                                $item->save();
                                break;
                            case 'tutor':  $item->type = 2;
                                $item->save();
                                break;
                            case 'parent':  $item->type = 3;
                                $item->save();
                                break;
                            default:  $item->type = 0;
                                $item->save();
                                break;
                        }
                        Bouncer::assign($roleInfo->name)->to($item);
                    } elseif (!$role->allow && Bouncer::is($item)->a($roleInfo->name)) {
                        Bouncer::retract($roleInfo->name)->from($item);
                    }
                }
            }
        }

        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item
        ], 200);
    }

    public function show($id)
    {
        $item = User::find($id);
        if(!$item) {
            return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        $item->roles = Role::Roles()->get();
        foreach($item->roles as $role) {
            if(Bouncer::is($item)->a($role->name)) {
                $role->checked = true;
            } else {
                $role->checked = false;
            }
        }

        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $item
        ], 200);
    }

    // recently edited
    public function destroy($id)
    {

        $item = User::find($id);
        if(!$item) {
            return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        if($item->type == 2) {
            $group_class = $item->groupClasses()->count();
            if ($group_class > 0) {
                return response([
                 'success' => false,
                 'message' => 'tutor-can-not-be-deleted-has-group-class',
                 'msg-code' => '200'
        ], 200);
            }

        }

        $item->email =  $item->id . '>>' . $item->email;
        $item->save();
        $courses = $item->courses()->get();
        if(count($courses) > 0) {
            $item->courses()->delete();
        } //foreach($courses as $course) $course->delete();
        $item->delete();

        return response([
                'success' => true,
                'message' => 'item-deleted-successfully'
        ], 200);
        ;
    }

    public function tutorFavorite(Request $request){
        $user = \Auth::user();
        // $limit = setDataTablePerPageLimit($request->limit);

        $favorites = UserFavorite::with('tutor','course.langs','course.imageInfo', 'group_class.langs', 'group_class.imageInfo')->where('user_id',$user->id)->orderBy('id','desc')->get();

        $filtered = $favorites->filter(function ($fav) {
            if ($fav->type == 1 && $fav->tutor === null) return false;
            if ($fav->type == 2 && $fav->course === null) return false;
            if ($fav->type == 3 && $fav->group_class === null) return false;
            return true;
        });

        foreach ($filtered as $fav) {
            if ($fav->type == 1) {
                unset($fav->course, $fav->group_class);
            } elseif ($fav->type == 2) {
                unset($fav->tutor, $fav->group_class);
            } else {
                unset($fav->tutor, $fav->course);
            }
        }
        
        return response([
                'success' => true,
                'resultl'=> [
                    'data' =>  $filtered->values(),
                ],
                'message' => 'item-list-successfully'
        ] , 200);
    }
    
    public function getUsers(Request $request)
    {
        $users = User::select('users.id', 'users.name')->get();

        return response([
                'success' => true,
                'message' => 'users-listed-successfully',
                'result' => $users
        ], 200);
    }

}