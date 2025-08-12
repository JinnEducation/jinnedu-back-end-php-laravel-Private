<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Tutor;
use App\Models\User;
use App\Models\UserAbout;
use App\Models\UserDescription;
use App\Models\UserVideo;
use App\Models\UserHourlyPrice;
use App\Models\UserCertification;
use App\Models\UserAvailability;
use App\Models\LoginSessionLog;
use App\Models\GroupClassStudent;
use App\Models\GroupClass;

use Bouncer;
use Illuminate\Support\Facades\Validator;
use Mail;
use DateTime;
class TutorController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'type' => 'required',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $data = $request->only(['email','password']);//,'password'
        $data['password'] =  bcrypt($data['password']);
        $tutor = User::factory()->create($data);

        $token = $tutor->createToken('main')->plainTextToken;
        $tutor->remember_token = $token;
        $tutor->name = '';
        $tutor->type = 2;
        $tutor->save();

        $log = new LoginSessionLog();
        $log->user_id = $tutor->id;
        $log->session = $token;
        $log->ipaddress = $request->ip();
        $log->browser = $request->userAgent();
        $log->os = 'register';
        $log->save();

        return response([
            'tutor' => $tutor,
            //'navigation' => $this->navigation(),
            'token' => $token
        ]);
    }
    //========================================================
    public function setAbout(Request $request)
    {
        $data = $request->only(['first_name','last_name','phone','country_id','level_id','language_id','subject_id','experience_id','situation_id','age','date_of_birth']);//,'password'
        $user = Auth::user();

        $tutor = Tutor::find($user->id);
        if(!$tutor) {
            return response([
                    'success' => false,
                    'message' => 'tutor-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        $data['user_id'] = $user->id;
        $data['age'] = 0;

        $about = UserAbout::where('user_id', $user->id)->first();

        if(!$about) {
            $about = UserAbout::create($data);
        } else {
            $about->update($data);
        }

        return response([
            'success' => true,
            'message' => 'Set about successfully',
            'result' => $about
        ], 200);
    }

    public function getAbout()
    {
        $user = Auth::user();

        $about = UserAbout::where('user_id', $user->id)->first();

        if(!$about) {
            return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $about
        ], 200);
    }
    //========================================================
    public function setDescription(Request $request)
    {
        $data = $request->only(['headline','interests','experience','specialization','specialization_id','methodology','motivation']);//,'password'
        $user = Auth::user();

        $tutor = Tutor::find($user->id);
        if(!$tutor) {
            return response([
                    'success' => false,
                    'message' => 'tutor-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        $data['user_id'] = $user->id;

        $description = UserDescription::where('user_id', $user->id)->first();

        if(!$description) {
            $description = UserDescription::create($data);
        } else {
            $description->update($data);
        }
    
        return response([
            'success' => true,
            'message' => 'Set description successfully',
            'result' => $description
        ], 200);
    }

    public function getDescription()
    {
        $user = Auth::user();

        $description = UserDescription::where('user_id', $user->id)->first();

        if(!$description) {
            return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $description
        ], 200);
    }
    //========================================================
    public function setVideo(Request $request)
    {
        $data = $request->only(['youtube_url']);//,'password'
        $user = Auth::user();

        $tutor = Tutor::find($user->id);
        if(!$tutor) {
            return response([
                    'success' => false,
                    'message' => 'tutor-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }


        $checkImageResult = checkAllowFile($request->attachment, ['mp4'], 'videos');
        //dd($checkImageResult);

        if($checkImageResult == 'empty-img') {
            return response([
                    'success' => false,
                    'message' => 'empty-image',
                    'msg-code' => '111'
            ], 200);
        }

        if($checkImageResult == 'error-ext') {
            return response([
                    'success' => false,
                    'message' => 'error-ext',
                    'msg-code' => '111'
            ], 200);
        }

        $data['user_id'] = $user->id;

        $video = UserVideo::where('user_id', $user->id)->first();

        $data['video_path'] = '';
        if(!empty($request->attachment)) {
            $data['video_path'] = uploadFile($request->attachment, ['mp4'], 'videos');
        }

        if(!$video) {
            $video = UserVideo::create($data);
        } else {
            $video->update($data);
        }
                
        return response([
            'success' => true,
            'message' => 'Video uploaded successfully',
            'result' => $video
        ], 200);
    }

    public function getVideo()
    {
        $user = Auth::user();

        $video = UserVideo::where('user_id', $user->id)->first();

        if(!$video) {
            return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $video
        ], 200);
    }
    //========================================================
    public function setHourlyPrice(Request $request)
    {
        $data = $request->only(['price']);//,'password'
        $user = Auth::user();

        $tutor = Tutor::find($user->id);
        if(!$tutor) {
            return response([
                    'success' => false,
                    'message' => 'tutor-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        $data['user_id'] = $user->id;

        $price = UserHourlyPrice::where('user_id', $user->id)->first();

        if(!$price) {
            $price = UserHourlyPrice::create($data);
        } else {
            $price->update($data);
        }

        return response([
            'success' => true,
            'message' => 'Price Set successfully',
            'result' => $price
        ], 200);
    }


    public function getHourlyPrice()
    {
        $user = Auth::user();

        $price = UserHourlyPrice::where('user_id', $user->id)->first();

        if(!$price) {
            return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $price
        ], 200);
    }
    //========================================================
    public function uploadPhoto(Request $request)
    {
        $data = $request->only(['photo']);//,'password'
        $user = Auth::user();

        $imageUrl = uploadImg($data['photo']);
        $user->avatar = $imageUrl;
        $user->save();
                
        return response([
            'success' => true,
            'message' => 'Photo uploaded successfully',
            'result' => ['url' => $user->avatar]
        ], 200);
    }

    public function photoUrl()
    {
        $user = Auth::user();

        return response([
            'success' => true,
            'message' => 'item-showen-successfully',
            'result' => ['url' => $user->avatar]
        ], 200);
    }
    //========================================================

    //========================================================
    public function setCertification(Request $request)
    {
        $data = $request->only(['subject_id','certificate','description','issued_by','years_from','years_to']);
        $user = Auth::user();

        $tutor = Tutor::find($user->id);
        if(!$tutor) {
            return response([
                    'success' => false,
                    'message' => 'tutor-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        $data['user_id'] = $user->id;
        $data['attachment'] = '';
        if(!empty($request->attachment)) $data['attachment'] = uploadFile($request->attachment,['pdf','doc','docx'],'certifications');
                
        $certification = UserCertification::where('user_id', $user->id)->first();

        if(!$certification) {
            $certification = UserCertification::create($data);
        } else {
            $certification->update($data);
        }
    
        return response([
            'success' => true,
            'message' => 'Set certification successfully',
            'result' => $certification
        ], 200);
    }

    public function getCertification()
    {
        $user = Auth::user();

        $certification = UserCertification::where('user_id', $user->id)->first();

        if(!$certification) {
            return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $certification
        ], 200);
    }
    //========================================================
    
    //========================================================
    public function setAvailability(Request $request, $id=0)
    {
        $data = $request->only(['timezone_id','day_id','status','hour_from','hour_to']);
        $user = Auth::user();

        $tutor = Tutor::find($user->id);
        if(!$tutor) {
            return response([
                    'success' => false,
                    'message' => 'tutor-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        $data['user_id'] = $user->id;
                
        $availability = UserAvailability::where('id', $id)->first();

        if(!$availability) {

            $availability = UserAvailability::create($data);

        } else {

            if($availability->user_id != $user->id) return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ] , 200);

            $availability->update($data);
        }
    
        return response([
            'success' => true,
            'message' => 'Set certification successfully',
            'result' => $availability
        ], 200);
    }

    public function getAvailability()
    {
        $user = Auth::user();

        $availability = UserAvailability::where('user_id', $user->id)->get();

        if(!$availability) {
            return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $availability
        ], 200);
    }

    public function getAvailabilityByTutorId($tutor_id){

        $availability = UserAvailability::where('user_id', $tutor_id)->get();

        if(!$availability) {
            return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $availability
        ], 200);
    }

    public function deleteAvailability($id)
    {
        $user = Auth::user();
        $item = UserAvailability::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        if($item->user_id!=$user->id) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        $item->delete();
        
        
        return response([
                'success' => true,
                'message' => 'item-deleted-successfully'
        ] , 200);
    }
    //========================================================

    public function getTutorReviews(Request $request, $tutor_id){

        $limit = setDataTablePerPageLimit($request->limit);

        $tutor = Tutor::find($tutor_id);
        if(!$tutor) {
            return response([
                    'success' => false,
                    'message' => 'tutor-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        $reviews = $tutor->reviews();
        $reviews = paginate($reviews, $limit);

        return response([
                'success' => true,
                'message' => 'reviews-retrieved-successfully',
                'result' => $reviews
        ], 200);
    }

    //========================================================
    public function mostPopular(Request $request)
    {
        $limit = setDataTablePerPageLimit($request->limit);
        
        $topGroupClassIds = GroupClassStudent::select('class_id', \DB::raw('COUNT(student_id) as student_count'))
            ->groupBy('class_id')
            ->orderByDesc('student_count')
            ->limit(3)
            ->pluck('class_id')
            ->toArray();

        $topTutorsIds = GroupClass::whereIn('id', $topGroupClassIds)->pluck('tutor_id')->toArray();

        $topTutors = Tutor::select('tutors.name', 'tutors.id', 'tutors.type', 'tutors.avatar', 'tutors.online')->whereIn('id', $topTutorsIds);
    
        $topTutors->distinct();
        $topTutors = paginate($topTutors, $limit);

        foreach($topTutors as $tutor) {
            $tutor->videos =  $tutor->videos()->get();
            $tutor->descriptions =  $tutor->descriptions()->get();
            $tutor->rating = $tutor->reviews()->avg('stars');
            //$tutor->reviews = $tutor->reviews()->get();
            //======================================================
            $tutor->abouts =  $tutor->abouts()->get();
            if($tutor->abouts) {
                foreach($tutor->abouts as $about) {
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

        
        $data = [
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $topTutors
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

}