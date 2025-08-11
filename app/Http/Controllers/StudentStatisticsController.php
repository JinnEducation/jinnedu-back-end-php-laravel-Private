<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tutor;
use App\Models\Order;
use App\Models\GroupClass;
use App\Models\GroupClassStudent;
use App\Models\Student;
use App\Models\Conference;
use App\Models\ConferenceComplaint;
use App\Models\Payout;
use App\Models\TutorFinance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
class StudentStatisticsController extends Controller
{
    public function studentInfo(Request $request) {
        
        $student = User::where('id', $request->student_id)->first();
        if(!$student){
             return response([
                'success' => false,
                'message' => 'student-dose-not-exist',
                'msg-code' => '222'
            ] , 200);
        }
        
        $wallet = $student->wallets()->first();

        $balance = $wallet ? $wallet->balance : 0;
        $groupclassCount = Order::where('ref_type', 1)->where('user_id', $student->id)->count();
        $trialLessonCount = Order::where('ref_type', 3)->where('user_id', $student->id)->count();
        $privateLessonCount = Order::where('ref_type', 4)->where('user_id', $student->id)->count();

        return response([
            'balance' => $balance,
            'group_class_count'=>$groupclassCount,
            'trial_lesson_count'=>$trialLessonCount,
            'private_lesson_count'=>$privateLessonCount
        ]);

    }

    public function getStudentGroupClassOrders(Request $request){

        $user = User::find($request->student_id);

        if(!$user){
            return response()->json([
               'success' => false,
               'message' => 'User not found',
               'msg-code' => '111'
            ], 200);
        }

        $limit = setDataTablePerPageLimit($request->limit);

        $items = Order::where('user_id', $user->id)->where('ref_type', 1)->distinct()->pluck('ref_id')->toArray();
        
        $group_classes = GroupClass::select('id', 'name', 'classes', 'price', 'image')->whereIn('id', $items);

        $group_classes = paginate($group_classes, $limit);

        return response([
               'success' => true,
               'message' => 'item-listed-successfully',
               'result' => $group_classes
        ], 200);
    }

    public function getStudentPrivateLessonOrders(Request $request){

        $user = User::find($request->student_id);

        if(!$user){
            return response()->json([
               'success' => false,
               'message' => 'User not found',
               'msg-code' => '111'
            ], 200);
        }

        $limit = setDataTablePerPageLimit($request->limit);

        $items = Order::select('id', 'price', 'user_id')->where('user_id', $user->id)->where('ref_type', 4);
        
        $items = paginate($items, $limit);

        return response([
               'success' => true,
               'message' => 'item-listed-successfully',
               'result' => $items
        ], 200);
    }
}
