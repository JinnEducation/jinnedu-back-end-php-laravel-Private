<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ConferenceExport;
use App\Model\Conference;
use App\Exports\RevenueReportExport;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class ReportController extends Controller
{
    
    public function studentsConferenceReport(Request $request){

        switch($request->type) {
            case 'completed':
                return $this->completedConference($request);
            case 'with_complaints':
                return $this->conferenceWithComplaints($request);
            case 'postponed':
                return $this->postponedConferences($request);
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }

    }

    public function revenueReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
            'type' => ['required', 'in:daily,weekly,monthly'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 200);
        }

        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to)->endOfDay();

        $selectDate = match ($request->type) {
            'weekly' => DB::raw('YEARWEEK(orders.created_at) as period'),
            'monthly' => DB::raw("DATE_FORMAT(orders.created_at, '%Y-%m') as period"),
            default => DB::raw('DATE(orders.created_at) as period'),
        };

        $revenues = DB::table('orders')
            ->leftJoin('tutor_finances', 'orders.id', '=', 'tutor_finances.order_id')
            ->whereBetween('orders.created_at', [$from, $to])
            ->select(
                $selectDate,
                DB::raw('COUNT(DISTINCT orders.id) as orders_count'),
                DB::raw('SUM(orders.price) as total_amount'),
                DB::raw('SUM(tutor_finances.fee) as total_expenses'),
                DB::raw('SUM(orders.price - IFNULL(tutor_finances.fee, 0)) as net_revenue')
            )
            ->groupBy('period')
            ->orderBy('period');

        if ($request->export) {
            return Excel::download(new RevenueReportExport($revenues->get()), 'revenue_report.xlsx');
        }

        $limit = setDataTablePerPageLimit($request->limit);

        $conferences = paginate($revenues, $limit);

        return response([
               'success' => true,
               'message' => 'item-listed-successfully',
               'result' => $revenues
        ], 200);
    }


    public function completedConference(Request $request){

        $conferences = Conference::when($request->student_id, function($query) use ($request) {
                    $query->where('student_id', $request->student_id);
                })
                ->doesntHave('complaints')
                ->where('end_date_time', '<', now())
                ->whereHas('attendances', function ($query) {
                    $query->where('status', 1);
                });

        if($request->from_date){
            $conferences->whereDate('created_at', '>=', $request->from_date);
        }
        
        if($request->to_date){
            $conferences->whereDate('created_at', '<=', $request->to_date);
        }


        if($request->export) {
            return Excel::download(new ConferenceExport($conferences->get()), 'completed_conferences.xlsx');
        }
                

        $limit = setDataTablePerPageLimit($request->limit);

        $conferences = paginate($conferences, $limit);

        return response([
               'success' => true,
               'message' => 'item-listed-successfully',
               'result' => $conferences
        ], 200);
    }

    public function conferenceWithComplaints(Request $request)
    {

        $conferences = Conference::when($request->student_id, function($query) use ($request) {
                $query->where('student_id', $request->student_id);
            })->has('complaints');


        if($request->from_date){
            $conferences->whereDate('created_at', '>=', $request->from_date);
        }
        
        if($request->to_date){
            $conferences->whereDate('created_at', '<=', $request->to_date);
        }

        
        if($request->export) {
            return Excel::download(new ConferenceExport($conferences->get()), 'conference_with_complaints.xlsx');
        }
                

        $limit = setDataTablePerPageLimit($request->limit);

        $conferences = paginate($conferences, $limit);

        return response([
               'success' => true,
               'message' => 'item-listed-successfully',
               'result' => $conferences
        ], 200);

    }

    public function postponedConferences(Request $request)
    {
        $conferences = Conference::when($request->student_id, function($query) use ($request) {
                    $query->where('student_id', $request->student_id);
            })
            ->where(function ($query) {
                $query->where('tutor_change_date', 1)
                    ->orWhere('student_change_date', 1);
            })->where('start_date_time', '>', now());

        if($request->from_date){
            $conferences->whereDate('created_at', '>=', $request->from_date);
        }
        
        if($request->to_date){
            $conferences->whereDate('created_at', '<=', $request->to_date);
        }

        
        if($request->export) {
            return Excel::download(new ConferenceExport($conferences->get()), 'postponed_conferences.xlsx');
        }
                

        $limit = setDataTablePerPageLimit($request->limit);

        $conferences = paginate($conferences, $limit);

        return response([
               'success' => true,
               'message' => 'item-listed-successfully',
               'result' => $conferences
        ], 200);
    }

}
