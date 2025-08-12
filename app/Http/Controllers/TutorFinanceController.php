<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tutor;
use App\Models\TutorFinance;
use App\Models\GroupClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
class TutorFinanceController extends Controller
{
    public function index(Request $request){
        $limit = setDataTablePerPageLimit($request->limit);

        $tutor_finances = TutorFinance::query();

        if($request->tutor_id){
            $tutor_finances->where('tutor_id', $request->tutor_id);
        }

        if($request->ref_type){
            $tutor_finances->where('ref_type', $request->ref_type);
        }

        if($request->status){
            $tutor_finances->where('status', $request->status);
        }

        if($request->from_date){
            $tutor_finances->whereDate('created_at', '>=', $request->from_date);
        }
        
        if($request->to_date){
            $tutor_finances->whereDate('created_at', '<=', $request->to_date);
        }

        $tutor_finances = paginate($tutor_finances, $limit);

        return response([
               'success' => true,
               'message' => 'item-listed-successfully',
               'result' => $tutor_finances
        ], 200);
    }

    public function myIndex(Request $request){

        $user = Auth::user();

        $limit = setDataTablePerPageLimit($request->limit);

        $tutor_finances = TutorFinance::where(['tutor_id' => $user->id]);

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
}
