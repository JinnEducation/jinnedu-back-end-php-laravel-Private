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
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
class TutorStatisticsController extends Controller
{
    public function getTutors(Request $request) {

        $limit = setDataTablePerPageLimit($request->limit);

        $tutors = Tutor::select('tutors.id', 'tutors.name', 'tutors.avatar', 'tutors.email');

        $tutors = paginate($tutors, $limit);

        foreach($tutors as $tutor) {

            $wallet = $tutor->wallets()->first();

            $balance = $wallet ? $wallet->balance : 0;
            $groupclassCount = GroupClass::where('tutor_id', $tutor->id)->count();
            $privateLessonCount = Order::where('ref_type', 4)->where('ref_id', $tutor->id)->count();

            $tutor->balance = $balance;
            $tutor->group_class_count = $groupclassCount;
            $tutor->private_lesson_count = $privateLessonCount;
        }

        return response([
               'success' => true,
               'message' => 'item-listed-successfully',
               'result' => $tutors
        ], 200);

    }

    public function tutorInfo(Request $request) {
        
        $tutor = User::where('id', $request->user_id)->first();
        if(!$tutor){
             return response([
                'success' => false,
                'message' => 'user-dose-not-exist',
                'msg-code' => '222'
            ] , 200);
        }
        
        $wallet = $tutor->wallets()->first();

        $balance = $wallet ? $wallet->balance : 0;
        $groupclassCount = GroupClass::where('tutor_id', $tutor->id)->count();
        $trialLessonCount = Order::where('ref_type', 3)->where('ref_id', $tutor->id)->count();
        $privateLessonCount = Order::where('ref_type', 4)->where('ref_id', $tutor->id)->count();
        $withdraw_amount = Payout::where(['tutor_id'=>$request->user_id, 'status'=>'P'])->sum('amount');

        return response([
            'balance' => $balance,
            'withdraw_amount' => $withdraw_amount,
            'group_class_count'=>$groupclassCount,
            'trial_lesson_count'=>$trialLessonCount,
            'private_lesson_count'=>$privateLessonCount
        ]);

    }

    public function getTutorGroupClassOrders(Request $request){

        $tutor = Tutor::find($request->tutor_id);

        if(!$tutor){
            return response()->json([
               'success' => false,
               'message' => 'Tutor not found',
               'msg-code' => '111'
            ], 200);
        }

        $limit = setDataTablePerPageLimit($request->limit);

        $items = Order::where('tutor_id', $tutor->id)->where('ref_type', 1)->distinct()->pluck('ref_id')->toArray();
        
        $group_classes = GroupClass::select('id', 'name', 'classes', 'price', 'image')->whereIn('id', $items);

        $request->has_complaint ? $group_classes->whereHas('conferences.complaints') : $group_classes->whereDoesntHave('conferences.complaints');

        $group_classes = paginate($group_classes, $limit);
        
        foreach($group_classes as $group_class){

            //get group class students
            $students_ids = GroupClassStudent::where('class_id', $group_class->ref_id)->pluck('student_id')->toArray();
            $group_class->students = Student::whereIn('id', $students_ids)->get();

            //check if this group class has a complaint
            $conferences = Conference::where('ref_type', 1)->where('ref_id', $group_class->id)->get();
            //$group_class_conference_ids = $conferences->pluck('id')->toArray();
           // $group_class->hasComplaint = ConferenceComplaint::whereIn('conference_id', $group_class_conference_ids)->count() > 0 ? true : false;

            foreach ($conferences as $conference) {
                $conference->hasComplaint = ConferenceComplaint::where('conference_id', $conference->id)->count() > 0 ? true : false;
                $conference->feesTransferred = TutorFinance::where(['conference_id' => $conference->id, 'status' => "transferred"])->first() ? true : false;
                
                //conference fees
                $percentage = getSettingVal('group_class_fees');
                $sessionFees = $group_class->price / $group_class->classes;
                $tutorFees = $percentage * $sessionFees / 100;
                
                $conference->tutor_fees = $tutorFees;
            }
            $group_class->conferences = $conferences;
            $group_class->imageInfo;
            $group_class->tutor_precentage = getSettingVal('group_class_fees');
            $group_class->fee = $group_class->price * getSettingVal('group_class_fees') / 100;
        }

        return response([
               'success' => true,
               'message' => 'item-listed-successfully',
               'result' => $group_classes
        ], 200);
    }

    public function getTutorPrivateLessonOrders(Request $request){

        $tutor = Tutor::find($request->tutor_id);

        if(!$tutor){
            return response()->json([
               'success' => false,
               'message' => 'Tutor not found',
               'msg-code' => '111'
            ], 200);
        }

        $limit = setDataTablePerPageLimit($request->limit);

        $items = Order::select('id', 'price', 'user_id')->where('tutor_id', $tutor->id)->where('ref_type', 4)->where('ref_id', $tutor->id);
        
        $request->has_complaint ? $items->whereHas('conference.complaints') : $items->whereDoesntHave('conference.complaints');

        $items = paginate($items, $limit);

        foreach($items as $item){
            
            $item->student = $item->user;

            $private_lesson_conference = Conference::where('order_id', $item->id)->first();
            // $item->hasComplaint = $private_lesson_conference ? (ConferenceComplaint::where('conference_id', $private_lesson_conference->id)->count() > 0 ? true : false) : false;

            $item->conference = $private_lesson_conference;
            $item->tutor_precentage = getSettingVal('private_lesson_fees');
            $item->fee = $item->price * getSettingVal('private_lesson_fees') / 100;

            unset($item->user);
        }

        return response([
               'success' => true,
               'message' => 'item-listed-successfully',
               'result' => $items
        ], 200);
    }

    public function getTutorCompletedConference(Request $request){

        $tutor = Auth::user();

        $tutor = Tutor::find($tutor->id);

        if(!$tutor){
            return response()->json([
               'success' => false,
               'message' => 'Tutor not found',
               'msg-code' => '111'
            ], 200);
        }

        $limit = setDataTablePerPageLimit($request->limit);

        $conferences = Conference::where('tutor_id', $tutor->id)
                ->doesntHave('complaints')
                ->where('end_date_time', '<', now())
                ->whereHas('attendances', function ($query) {
                    $query->where('status', 1);
                });

        $conferences = paginate($conferences, $limit);


        return response([
               'success' => true,
               'message' => 'item-listed-successfully',
               'result' => $conferences
        ], 200);

    }

    public function getTutorConferenceWithComplaints(Request $request)
    {
        $tutor = auth()->user();

        $tutor = Tutor::find($tutor->id);

        if (!$tutor) {
            return response()->json([
                'success' => false,
                'message' => 'Tutor not found',
                'msg-code' => '111'
            ], 200);
        }

        $limit = setDataTablePerPageLimit($request->limit);

        $conferences = Conference::where('tutor_id', $tutor->id)->has('complaints');

        $conferences = paginate($conferences, $limit);

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $conferences
        ], 200);
    }

    public function getPostponedConferences(Request $request)
    {
        $tutor = auth()->user();

        $tutor = Tutor::find($tutor->id);

        if (!$tutor) {
            return response()->json([
                'success' => false,
                'message' => 'Tutor not found',
                'msg-code' => '111'
            ], 200);
        }

        $limit = setDataTablePerPageLimit($request->limit);

        $conferences = Conference::where('tutor_id', $tutor->id)
            ->where(function ($query) {
                $query->where('tutor_change_date', 1)
                    ->orWhere('student_change_date', 1);
            })->where('start_date_time', '>', now());

        $conferences = paginate($conferences, $limit);

        return response([
            'success' => true,
            'message' => 'postponed-conferences-listed-successfully',
            'result' => $conferences
        ], 200);
    }

    public function getTutorFinance(Request $request){

        $tutor = Tutor::find($request->tutor_id);

        if(!$tutor){
            return response()->json([
               'success' => false,
               'message' => 'Tutor not found',
               'msg-code' => '111'
            ], 200);
        }

        $limit = setDataTablePerPageLimit($request->limit);

        $tutor_finances = TutorFinance::select('id', 'tutor_id', 'ref_type', 'ref_id', 'percentage', 'fee')->where(['tutor_id' => $request->tutor_id, 'status' => 'pending']);

        $tutor_finances = paginate($tutor_finances, $limit);

        foreach ($tutor_finances as $tutor_finance) {
            
            if($tutor_finance->ref_type == 1){
                $tutor_finance->group_class = GroupClass::select('name')->where('id', $tutor_finance->ref_id)->first();
            }

        }

        return response([
               'success' => true,
               'message' => 'item-listed-successfully',
               'result' => $tutor_finances
        ], 200);

    }

    public function updateTutorFinanceStatus(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'status'   => ['required', 'in:transferred,rejected'],
            'note'   => ['nullable'] 
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 200);
        }

        $tutor_finance = TutorFinance::where('id', $id)->first();

        if(!$tutor_finance) {
            return response([
                    'success' => false,
                    'message' => 'tutor-finance-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        if($tutor_finance->status != 'pending') {
            return response([
                    'success' => false,
                    'message' => 'tutor-finance-has-been-'.$tutor_finance->status,
                    'msg-code' => '222'
            ], 400);
        }


        try {

            DB::beginTransaction();

            if($request->status == 'transferred') {
                $tutor = Tutor::find($tutor_finance->tutor_id);
                $wallet = $tutor->wallets()->first();
                $wallet->balance += $tutor_finance->fee;
                $wallet->save();
            }

            $tutor_finance->status = $request->status;
            $tutor_finance->note = $request->note;
            $tutor_finance->save();

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                    'success' => false,
                    'message' => $th->getMessage(),
                    'msg-code' => '444'
            ], 200);
        }

        return response([
                'success' => true,
                'message' => 'tutor-finance-updated-successfully'
        ], 200);

    }
    
    public function tutorTransferFeesToHisWallet(Request $request){
        
        $tutor_finance = TutorFinance::where(['conference_id' => $request->conference_id, 'status' => "transferred"])->first();
        
        
        if($tutor_finance){
            return response([
                'success' => false,
                'message' => 'this-conference-has-transferred-its-fees-to-the-tutor',
                'msg-code' => '333'
            ], 200);
        }
        
        try {

            DB::beginTransaction();

            $conference = Conference::where('id', $request->conference_id)->first();
            
            $groupClass = GroupClass::where('id', $conference->ref_id)->first();
            
            $percentage = getSettingVal('group_class_fees');
            
            $sessionFees = $groupClass->price / $groupClass->classes;
            
            $tutorFees = $percentage * $sessionFees / 100;
            
            TutorFinance::create([
                'tutor_id'=> $conference->tutor_id,
                'conference_id'=> $conference->id,
                'ref_type'=> 1,
                'ref_id' => $groupClass->id,
                'total'=> $groupClass->price,
                'percentage'=> $percentage,
                'fee'=> $tutorFees,
                'class_date' => $conference->date,
                'status' => "transferred"
            ]); 
            
            $tutor = Tutor::find($conference->tutor_id);
            $wallet = $tutor->wallets()->first();
            
            if($wallet){
                $wallet->balance += $tutorFees;
                $wallet->save(); 
            }else{
                $wallet->balance = $tutorFees;
                $wallet->save(); 
            }

                    
            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                    'success' => false,
                    'message' => $th->getMessage(),
                    'msg-code' => '444'
            ], 200);
        }

        return response([
                'success' => true,
                'message' => 'tutor-fees-transferred-successfully'
        ], 200);
    }
}
