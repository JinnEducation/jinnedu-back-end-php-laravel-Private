<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\Tutor;
use App\Models\UserAbout;
use App\Models\Subject;
use App\Models\Order;
use App\Models\GroupClass;
use App\Models\Student;

use Bouncer;
use Mail;

class StatisticsController extends Controller
{
    public function home()
    {
        $educational_services = Order::where('ref_type', 4)->count();
        $registered_students_count = Student::query()->count();
        $registered_tutors_count = Tutor::query()->count();
        $courses_count = GroupClass::where('status', 1)->where('tutor_id', '<>', null)->count();
        
        
        return response([
                'success' => true,
                'message' => 'statistics-showen-successfully',
                'result' => [             
                    'educational_services' => $educational_services,
                    'registered_students_count' => $registered_students_count,
                    'registered_tutors_count' => $registered_tutors_count,
                    'courses_count' => $courses_count
                ]
        ] , 200);
    }
    
    // old home
    // public function home()
    // {
    //     $experience_tutors = Tutor::query()->count();
    //     $tutors_nationalities = Tutor::select("country_id")->leftjoin('user_abouts','user_abouts.user_id','tutors.id')->distinct()->count();
    //     $five_stars_tutor_reviews=0;
    //     $subjects_taught =Subject::query()->count();
        
    //     $tutors = Tutor::all();
    //     foreach($tutors as $tutor){
    //         $tutor->rating = $tutor->reviews()->avg('stars');
    //         if($tutor->rating==5) $five_stars_tutor_reviews++;
    //     }
        
    //     return response([
    //             'success' => true,
    //             'message' => 'statistics-showen-successfully',
    //             'result' => [             
    //                 'experience_tutors' => $experience_tutors,
    //                 'tutors_nationalities' => $tutors_nationalities,
    //                 'five_stars_tutor_reviews' => $five_stars_tutor_reviews,
    //                 'subjects_taught' => $subjects_taught
    //             ]
    //     ] , 200);
    // }

}