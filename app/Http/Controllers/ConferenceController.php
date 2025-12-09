<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use DateTime;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\BraincertController;

use App\Models\Conference;
use App\Models\ConferenceLink;
use App\Models\ConferenceNote;
use App\Models\ConferenceFile;
use App\Models\ConferenceReview;
use App\Models\ConferenceComplaint;

use App\Models\User;
use App\Models\Tutor;
use App\Models\Student;
use App\Models\Parents;

use App\Models\GroupClass;
use App\Models\GroupClassDate;
use App\Models\GroupClassOutline;
use App\Models\GroupClassLang;
use App\Models\GroupClassStudent;

use App\Models\OurCourse;
use App\Models\OurCourseDate;
use App\Models\OurCourseLevel;
use App\Models\OurCourseLang;
use App\Models\OurCourseTutor;
use App\Models\TutorFinance;
use App\Models\ConferenceAttendance;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Bouncer;
use Mail;

class ConferenceController extends Controller
{
    public function createPrivateLessonConference($order)
    {
        $conference = new Conference;
        $conference->student_id = $order->user_id;
        $conference->tutor_id = $order->ref_id;

        $conference->ref_id = $order->ref_id;
        $conference->ref_type = $order->ref_type;
        $conference->order_id = $order->id;

        $conference->title = $order->note;
        $order->dates = json_decode($order->dates);

        $conference->start_date_time = $order->dates?->start_date_time;
        $conference->end_date_time   = date('Y-m-d H:i:s', strtotime($conference->start_date_time . ' +40 minutes'));

        $conference->date = $order->dates?->date;
        $conference->start_time = $order->dates?->start_date_time;
        $conference->end_time   = $conference->end_date_time;
        $conference->record = 3;
        $conference->timezone = 35;

        $conference->type = 'zoom'; // braincert
        $conference->status = 0;
        $conference->save();

        $postValues = array(
            'title' => $conference->title,
            'timezone' => 35,
            'start_time' => $conference->start_time,
            'end_time' => $conference->end_time,
            'date' => $conference->date,
            'record' => 3
        );


        // $braincert = new BraincertController;
        // $conference->response = $braincert->conferenceCreate($postValues);
        $zoom = new ZoomController;
        // $conference->response = $zoom->createMeeting($postValues);
        $result = $zoom->createMeeting($postValues);

        $conference->response = json_encode($result);
        // $conference->notes = json_encode($postValues);
        $conference->save();


        // $braincert = new BraincertController;
        // $conference->response = $braincert->conferenceCreate($postValues);
        //$notes = json_encode($postValues);
        // $conference->save();

        /*
        $braincert = json_encode($conference->response);
        if(isset($braincert) && $braincert->status=='ok') $conference->save();
        else response([
                    'success' => false,
                    'message' => 'conference-faild',
                    'result' => $conference->response
            ] , 400);
        */

        $conferences = Conference::where('order_id', $order->id)->get();
        return $conferences;
    }

    public function createOurCourseConferences($order)
    {
        $ourCourse = OurCourse::find($order->ref_id);
        $dates = json_decode($order->dates);
        //dd($dates);
        for ($i = 0; $i < $order->lessons; $i++) {
            $conference = new Conference;
            $conference->student_id = $order->user_id;
            $conference->tutor_id = $order->tutor_id;

            $conference->ref_id = $order->ref_id;
            $conference->ref_type = $order->ref_type;
            $conference->order_id = $order->id;

            $conference->title = $ourCourse->name;

            $conference->start_date_time = $dates[$i]->date;
            $conference->end_date_time   = date('Y-m-d H:i:s', strtotime($conference->start_date_time . ' +40 minutes'));

            $start_date_time = explode(' ', $conference->start_date_time);
            $end_date_time   = explode(' ', $conference->end_date_time);
            //echo $end_date_time;exit;
            //echo date("H:iA", strtotime($date_time[1]));exit;

            $conference->date = $start_date_time[0];
            $conference->start_time = date("h:iA", strtotime($start_date_time[1]));
            $conference->end_time   = date("h:iA", strtotime($end_date_time[1]));
            $conference->record = 3;
            $conference->timezone = 35;

            $conference->type = 'braincert';
            $conference->status = 0;
            $conference->save();

            $postValues = array(
                'title' => $conference->title,
                'timezone' => 35,
                'start_time' => $conference->start_time,
                'end_time' => $conference->end_time,
                'date' => $conference->date,
                'record' => 3
            );

            $braincert = new BraincertController;
            $conference->response = $braincert->conferenceCreate($postValues);
            //$conference->notes = json_encode($postValues);
            $conference->save();
        }

        $conferences = Conference::where('order_id', $order->id)->get();
        return $conferences;
    }

    public function addComplaint(Request $request, $id)
    {
        $user = Auth::user();

        $conference = Conference::where('id', $id)->whereRaw('(student_id=? and student_id<>0 and order_id<>0 and ref_type<>1) or (ref_id in (select class_id from group_class_students where student_id=?) and student_id=0 and order_id=0 and ref_type=1)', [$user->id, $user->id])->first();
        if (!$conference) return response([
            'success' => false,
            'message' => 'conference-not-exist',
            'msg-code' => '111'
        ], 200);

        if (empty($request->subject)) return response([
            'success' => false,
            'message' => 'subject-is-empty',
            'msg-code' => '333'
        ], 200);

        if (empty($request->note)) return response([
            'success' => false,
            'message' => 'note-is-empty',
            'msg-code' => '333'
        ], 200);

        $data = [];

        $data['subject'] = $request->subject;
        $data['note'] = $request->note;
        $data['reply_id'] = 0;
        $data['tutor_id'] = $conference->tutor_id;
        $data['student_id'] = $user->id;
        $data['user_id'] = $user->id;
        $data['conference_id'] = $id;
        $data['ipaddress'] = $request->ip();

        if (!empty($request->file)) {
            $file = uploadMedia($request->file, ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'exl', 'exlx', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'mp3', 'mp4'], 'conferences-complaints');
            $data = array_merge($data, $file);
        }

        $item = ConferenceComplaint::create($data);
        return response([
            'success' => true,
            'message' => 'item-added-successfully',
            'result' => $item,
            'data' => $data
        ], 200);
    }

    public function replyComplaint(Request $request, $confid, $compid)
    {
        $user = Auth::user();

        $conference = ConferenceComplaint::where('id', $compid)->where('user_id', $user->id)->where('conference_id', $confid)->first(); #
        if (!$conference) return response([
            'success' => false,
            'message' => 'item-not-exist',
            'msg-code' => '111'
        ], 200);

        if (empty($request->status)) return response([
            'success' => false,
            'message' => 'status-is-empty',
            'msg-code' => '222'
        ], 200);

        if (empty($request->note)) return response([
            'success' => false,
            'message' => 'note-is-empty',
            'msg-code' => '333'
        ], 200);

        $data = [];

        $data['status'] = $request->status;
        $data['note'] = $request->note;
        $data['reply_id'] = $compid;
        $data['tutor_id'] = 0;
        $data['student_id'] = 0;
        $data['user_id'] = $user->id;
        $data['conference_id'] = 0;
        $data['ipaddress'] = $request->ip();

        if (!empty($request->file)) {
            $file = uploadMedia($request->file, ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'exl', 'exlx', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'mp3', 'mp4'], 'conferences-complaints');
            $data = array_merge($data, $file);
        }

        $item = ConferenceComplaint::create($data);
        return response([
            'success' => true,
            'message' => 'item-added-successfully',
            'result' => $item,
            'data' => $data
        ], 200);
    }

    public function uploadFile(Request $request, $id)
    {
        $user = Auth::user();

        $conference = Conference::where('id', $id)->whereRaw('(student_id=? and student_id<>0 and order_id<>0 and ref_type<>1) or (ref_id in (select class_id from group_class_students where student_id=?) and student_id=0 and order_id=0 and ref_type=1)', [$user->id, $user->id])->first();
        if (!$conference) return response([
            'success' => false,
            'message' => 'conference-not-exist',
            'msg-code' => '111'
        ], 200);

        $data = [];
        if (empty($request->file)) return response([
            'success' => false,
            'message' => 'file-not-exist',
            'msg-code' => '333'
        ], 200);

        $data = uploadMedia($request->file, ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'exl', 'exlx', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'mp3', 'mp4'], 'conferences-files');
        $data['user_id'] = $user->id;
        $data['conference_id'] = $id;
        $data['ipaddress'] = $request->ip();
        $item = ConferenceFile::create($data);
        return response([
            'success' => true,
            'message' => 'item-added-successfully',
            'result' => $item,
            'data' => $data
        ], 200);
    }

    public function addNote(Request $request, $id)
    {
        $user = Auth::user();

        $conference = Conference::where('id', $id)->whereRaw('(student_id=? and student_id<>0 and order_id<>0 and ref_type<>1) or (ref_id in (select class_id from group_class_students where student_id=?) and student_id=0 and order_id=0 and ref_type=1)', [$user->id, $user->id])->first();
        if (!$conference) return response([
            'success' => false,
            'message' => 'conference-not-exist',
            'msg-code' => '111'
        ], 200);

        $data = [];
        if (empty($request->note)) return response([
            'success' => false,
            'message' => 'note-is-empty',
            'msg-code' => '333'
        ], 200);

        $data['note'] = $request->note;
        $data['user_id'] = $user->id;
        $data['conference_id'] = $id;
        $data['ipaddress'] = $request->ip();
        $item = ConferenceNote::create($data);
        return response([
            'success' => true,
            'message' => 'item-added-successfully',
            'result' => $item,
            'data' => $data
        ], 200);
    }

    public function viewComplaint(Request $request, $confid, $compid)
    {
        $user = Auth::user();

        $item = ConferenceComplaint::where('id', $compid)->where('user_id', $user->id)->where('conference_id', $confid)->first();

        if (!$item) return response([
            'success' => false,
            'message' => 'item-not-exist',
            'msg-code' => '111'
        ], 200);

        $item->user;
        $item->replies = $item->replies()->get();

        foreach ($item->replies as $reply) {
            $reply->user;
            if ($reply->user) $reply->user->email = null;
        }


        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $item
        ], 200);
    }

    public function myComplaints(Request $request, $id)
    {
        $user = Auth::user();

        $limit = setDataTablePerPageLimit($request->limit);

        $items = ConferenceComplaint::query();
        $items->where('user_id', $user->id)->where('conference_id', $id);
        if (!empty($request->q)) {
            $items->whereRaw(filterTextDB('subject') . ' like ?', ['%' . filterText($request->q) . '%']);
        }
        $items = $items->paginate($limit);

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items
        ], 200);
    }

    public function myFiles(Request $request, $id)
    {
        $user = Auth::user();

        $limit = setDataTablePerPageLimit($request->limit);

        $items = ConferenceFile::query();
        $items->where('user_id', $user->id)->where('conference_id', $id);
        if (!empty($request->q)) {
            $items->whereRaw(filterTextDB('name') . ' like ?', ['%' . filterText($request->q) . '%']);
        }
        $items = $items->paginate($limit);

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items
        ], 200);
    }

    public function myNotes(Request $request, $id)
    {
        $user = Auth::user();

        $limit = setDataTablePerPageLimit($request->limit);

        $items = ConferenceNote::query();
        $items->where('user_id', $user->id)->where('conference_id', $id);
        if (!empty($request->q)) {
            $items->whereRaw(filterTextDB('note') . ' like ?', ['%' . filterText($request->q) . '%']);
        }
        $items = $items->paginate($limit);

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items
        ], 200);
    }

    public function myLinks(Request $request, $id)
    {
        $user = Auth::user();

        $limit = setDataTablePerPageLimit($request->limit);

        $items = ConferenceLink::query();
        $items->where('user_id', $user->id)->where('conference_id', $id);
        if (!empty($request->q)) {
            $items->whereRaw(filterTextDB('title') . ' like ?', ['%' . filterText($request->q) . '%']);
        }
        $items = $items->paginate($limit);

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items
        ], 200);
    }

    public function adminIndex(Request $request)
    {
        $user = Auth::user();


        $limit = setDataTablePerPageLimit($request->limit);

        $items = Conference::query();

        if (!empty($request->tutor_id)) {
            $items->where('tutor_id', $request->tutor_id);
        }

        if (!empty($request->ref_type)) {
            $items->where('ref_type', $request->ref_type);
        }

        if (!empty($request->q)) {
            $items->whereRaw(filterTextDB('title') . ' like ?', ['%' . filterText($request->q) . '%']);
        }

        $items = $items->paginate($limit);
        foreach ($items as $item) {
            $item->tutor;

            if ($item->ref_type == 1) {
                $students_ids = GroupClassStudent::where('class_id', $item->ref_id)->pluck('student_id')->toArray();
                $item->students = Student::whereIn('id', $students_ids)->get();
            } else {
                $students = array();
                $students[] = $item->student;
                $item->students = $students;
                unset($item->student);
            }

            $item->rating =  $item->reviews()->avg('stars');
        }

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items
        ], 200);
    }

    public function studentIndex(Request $request)
    {
        $user = Auth::user();


        $limit = setDataTablePerPageLimit($request->limit);

        $items = Conference::query();
        $items->whereRaw('(student_id=? and student_id<>0 and order_id<>0 and ref_type<>1) or (ref_id in (select class_id from group_class_students where student_id=?) and student_id=0 and order_id=0 and ref_type=1)', [$user->id, $user->id]);
        if (!empty($request->q)) {
            $items->whereRaw(filterTextDB('title') . ' like ?', ['%' . filterText($request->q) . '%']);
        }

        /*return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items->toSql()
        ] , 200);*/

        $items = $items->paginate($limit);
        foreach ($items as $item) {
            $item->tutor;
            $item->rating =  $item->reviews()->avg('stars');
            $item->is_available = strtotime($item->date . ' ' . $item->start_time) >= time();
            // $item->attendance_status = ConferenceAttendance::where(['conference_id'=>$item->id, 'user_id'=>$user->id, 'status'=>1])->exists();
            $item->attendance_status = ConferenceAttendance::where(['conference_id' => $item->id, 'user_id' => $user->id])->exists();

            if ($item->ref_type == 1) {
                $item->group_class = GroupClass::select('id', 'name', 'slug')->where('id', $item->ref_id)->first();
            }
        }

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items
        ], 200);
    }

    public function tutorIndex(Request $request)
    {
        $user = Auth::user();


        $limit = setDataTablePerPageLimit($request->limit);

        $items = Conference::query();
        $items->where('tutor_id', $user->id);
        if (!empty($request->q)) {
            $items->whereRaw(filterTextDB('title') . ' like ?', ['%' . filterText($request->q) . '%']);
        }
        $items = $items->paginate($limit);
        foreach ($items as $item) {
            $item->tutor_name = $item->tutor->name;
            $item->student;
            $item->rating =  $item->reviews()->avg('stars');
            unset($item->tutor);
        }

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items
        ], 200);
    }

    public function studentChangeDate(Request $request, $id)
    {
        $user = Auth::user();

        $conference = Conference::where('id', $id)->whereRaw('TIMESTAMPDIFF(MINUTE,NOW(),`start_date_time`)>60 and student_change_date=0 and student_id=? and student_id<>0 and order_id<>0 and ref_type<>1', [$user->id])->first();
        if (!$conference) return response([
            'success' => false,
            'message' => 'change-date-dose-not-allow',
            'msg-code' => '111'
        ], 200);

        $period = 15;
        if ($conference->ref_type == 4 or $conference->ref_type == 2) $period = 40;

        $now = new DateTime("now");
        $start_date = $request->date;
        $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $period . ' minutes'));
        $book_start = new DateTime($start_date);
        $book_end   = new DateTime($end_date);
        if ($book_start <= $now)
            return response([
                'success' => false,
                'message' => 'book-date-is-old',
                'msg-code' => '555',
                'book_start' => $book_start,
                'now' => $now
            ], 200);

        $checkConflictTutorDate = Conference::where('tutor_id', $conference->tutor_id)->whereRaw('(start_date_time=? or end_date_time=? or (? > start_date_time and ? < end_date_time) or (? > start_date_time and ? < end_date_time))', [$start_date, $end_date, $start_date, $start_date, $end_date, $end_date])->first();
        if ($checkConflictTutorDate)
            return response([
                'success' => false,
                'message' => 'tutor-date-conflict',
                'msg-code' => '666',
                'start_date' => $start_date,
                'end_date' => $end_date
            ], 200);

        $checkConflictStudentDate = Conference::whereRaw('((student_id=? and student_id<>0 and order_id<>0 and ref_type<>1) or (ref_id in (select class_id from group_class_students where student_id=?) and student_id=0 and order_id=0 and ref_type=1)) and (start_date_time=? or end_date_time=? or (? > start_date_time and ? < end_date_time) or (? > start_date_time and ? < end_date_time))', [$user->id, $user->id, $start_date, $end_date, $start_date, $start_date, $end_date, $end_date])->first();
        if ($checkConflictStudentDate)
            return response([
                'success' => false,
                'message' => 'student-date-conflict',
                'msg-code' => '777',
                'start_date' => $start_date,
                'end_date' => $end_date,
                'conference' => $checkConflictStudentDate
            ], 200);

        $conference->student_change_date = 1;
        $conference->old_student_start_date_time = $conference->start_date_time;
        $conference->start_date_time = $start_date;
        $conference->end_date_time = $end_date;

        $start_date_time = explode(' ', $conference->start_date_time);
        $end_date_time   = explode(' ', $conference->end_date_time);
        //echo $end_date_time;exit;
        //echo date("H:iA", strtotime($date_time[1]));exit;

        $conference->date = $start_date_time[0];
        $conference->start_time = date("h:iA", strtotime($start_date_time[1]));
        $conference->end_time   = date("h:iA", strtotime($end_date_time[1]));
        $conference->save();

        $tutor_finance = TutorFinance::where('order_id', $conference->order_id)->first();

        if ($tutor_finance) {
            $tutor_finance->class_date = $conference->start_time;
            $tutor_finance->save();
        }

        $postValues = array(
            'title' => $conference->title,
            'timezone' => 35,
            'start_time' => $conference->start_time,
            'end_time' => $conference->end_time,
            'date' => $conference->date,
            'record' => 3
        );

        $braincert = new BraincertController;
        $conference->response = $braincert->conferenceCreate($postValues);
        //$conference->notes = json_encode($postValues);
        $conference->save();

        // إرسال إشعار للمدرّس بأن الطالب غيّر الموعد
        if ($conference->tutor_id) {
            $tutor = User::find($conference->tutor_id);
            if ($tutor && $tutor->fcm) {
                $info = [
                    'type' => 'schedule_change',
                    'conference_id' => $conference->id,
                    'new_date' => $conference->date,
                    'new_time' => $conference->start_time . ' - ' . $conference->end_time
                ];

                sendFCMNotification(
                    'Schedule Changed',
                    'Student has changed the lesson schedule to ' . $conference->date . ' at ' . $conference->start_time,
                    $tutor->fcm,
                    $info,
                    $tutor->id   // 👈 المدرّس صاحب الإشعار
                );
            }
        }

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $conference
        ], 200);
    }

    public function tutorChangeDate(Request $request, $id)
    {
        $user = Auth::user();

        $conference = Conference::where('id', $id)->where('tutor_id', $user->id)->whereRaw('TIMESTAMPDIFF(MINUTE,NOW(),`start_date_time`)>60 and tutor_change_date=0 and student_id>0 and student_id<>0 and order_id<>0 and ref_type<>1', [])->first();
        if (!$conference) return response([
            'success' => false,
            'message' => 'change-date-dose-not-allow',
            'msg-code' => '111'
        ], 200);


        $period = 15;
        if ($conference->ref_type == 4 or $conference->ref_type == 2) $period = 40;

        $now = new DateTime("now");
        $start_date = $request->date;
        $end_date = date('Y-m-d H:i:s', strtotime($start_date . ' +' . $period . ' minutes'));
        $book_start = new DateTime($start_date);
        $book_end   = new DateTime($end_date);
        if ($book_start <= $now)
            return response([
                'success' => false,
                'message' => 'book-date-is-old',
                'msg-code' => '555',
                'book_start' => $book_start,
                'now' => $now
            ], 200);

        $checkConflictTutorDate = Conference::where('tutor_id', $conference->tutor_id)->whereRaw('(start_date_time=? or end_date_time=? or (? > start_date_time and ? < end_date_time) or (? > start_date_time and ? < end_date_time))', [$start_date, $end_date, $start_date, $start_date, $end_date, $end_date])->first();
        if ($checkConflictTutorDate)
            return response([
                'success' => false,
                'message' => 'tutor-date-conflict',
                'msg-code' => '666',
                'start_date' => $start_date,
                'end_date' => $end_date
            ], 200);

        $checkConflictStudentDate = Conference::whereRaw('((student_id=? and student_id<>0 and order_id<>0 and ref_type<>1) or (ref_id in (select class_id from group_class_students where student_id=?) and student_id=0 and order_id=0 and ref_type=1)) and (start_date_time=? or end_date_time=? or (? > start_date_time and ? < end_date_time) or (? > start_date_time and ? < end_date_time))', [$user->id, $user->id, $start_date, $end_date, $start_date, $start_date, $end_date, $end_date])->first();
        if ($checkConflictStudentDate)
            return response([
                'success' => false,
                'message' => 'student-date-conflict',
                'msg-code' => '777',
                'start_date' => $start_date,
                'end_date' => $end_date,
                'conference' => $checkConflictStudentDate
            ], 200);

        $conference->tutor_change_date = 1;
        $conference->old_tutor_start_date_time = $conference->start_date_time;
        $conference->start_date_time = $start_date;
        $conference->end_date_time = $end_date;

        $start_date_time = explode(' ', $conference->start_date_time);
        $end_date_time   = explode(' ', $conference->end_date_time);
        //echo $end_date_time;exit;
        //echo date("H:iA", strtotime($date_time[1]));exit;

        $conference->date = $start_date_time[0];
        $conference->start_time = date("h:iA", strtotime($start_date_time[1]));
        $conference->end_time   = date("h:iA", strtotime($end_date_time[1]));
        $conference->save();

        $tutor_finance = TutorFinance::where('order_id', $conference->order_id)->first();

        if ($tutor_finance) {
            $tutor_finance->class_date = $conference->start_time;
            $tutor_finance->save();
        }

        $postValues = array(
            'title' => $conference->title,
            'timezone' => 35,
            'start_time' => $conference->start_time,
            'end_time' => $conference->end_time,
            'date' => $conference->date,
            'record' => 3
        );

        $braincert = new BraincertController;
        $conference->response = $braincert->conferenceCreate($postValues);
        //$conference->notes = json_encode($postValues);
        $conference->save();

        // إرسال إشعار للطالب بأن المدرّس غيّر الموعد
        if ($conference->student_id) {
            $student = User::find($conference->student_id);
            if ($student && $student->fcm) {
                $info = [
                    'type' => 'schedule_change',
                    'conference_id' => $conference->id,
                    'new_date' => $conference->date,
                    'new_time' => $conference->start_time . ' - ' . $conference->end_time
                ];
               sendFCMNotification(
    'Schedule Changed',
    'Your tutor has changed the lesson schedule to ' . $conference->date . ' at ' . $conference->start_time,
    $student->fcm,
    $info,
    $student->id
);
            }
        }

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $conference
        ], 200);
    }

    public function createStudentLink(Request $request, $id)
    {
        $user = Auth::user();

        $conference = Conference::whereRaw('(id=? and student_id=? and student_id<>0 and order_id<>0 and ref_type<>1) or (ref_id in (select class_id from group_class_students where student_id=?) and id=? and student_id=0 and order_id=0 and ref_type=1)', [$id, $user->id, $user->id, $id])->first();

        if (!$conference) return response([
            'success' => false,
            'message' => 'conference-dose-not-exist',
            'msg-code' => '111'
        ], 200);

        $response = json_decode($conference->response);
        if (!$response) return response([
            'success' => false,
            'message' => 'response-dose-not-exist',
            'msg-code' => '222'
        ], 200);

        // if($response->status!='ok') return response([
        //         'success' => false,
        //         'message' => 'status-dose-not-ok',
        //         'msg-code' => '333'
        // ] , 200);

        $conferenceLink = new ConferenceLink;

        $conferenceLink->ref_id = $conference->ref_id;
        $conferenceLink->ref_type = $conference->ref_type;
        $conferenceLink->order_id = $conference->order_id;
        $conferenceLink->conference_id = $conference->id;

        $conferenceLink->class_id = $conference->class_id;
        $conferenceLink->user_name = $user->name;

        $conferenceLink->is_teacher = 0;

        $conferenceLink->user_id = $user->id;

        $conferenceLink->lesson_name = 'at ' . $conference->date . ' from ' . $conference->start_time . ' to ' . $conference->end_time;
        $conferenceLink->course_name = $conference->title;

        $conferenceLink->type = 'zoom'; //braincert
        $conferenceLink->status = 0;
        $conferenceLink->save();

        $postValues = array(
            'class_id' => $conferenceLink->class_id,
            'userId' => $conferenceLink->user_id,
            'userName' => $conferenceLink->user_name,
            'isTeacher' => $conferenceLink->is_teacher,
            'lessonName' => $conferenceLink->lesson_name,
            'courseName' => $conferenceLink->course_name
        );

        // $braincert = new BraincertController;
        // $conferenceLink->response = $braincert->conferenceLink($postValues);
        $conferenceLink->response = $conference->response;

        //$conferenceLink->notes = json_encode($postValues);
        $conferenceLink->save();

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $conferenceLink
        ], 200);
    }

    public function createTutorLink(Request $request, $id)
    {
        $user = Auth::user();

        $conference = Conference::where('id', $id)->where('tutor_id', $user->id)->first();
        if (!$conference) return response([
            'success' => false,
            'message' => 'conference-dose-not-exist',
            'msg-code' => '111'
        ], 200);


        $response = json_decode($conference->response);
        $response = $response->data;
        if (!$response) return response([
            'success' => false,
            'message' => 'response-dose-not-exist',
            'msg-code' => '222'
        ], 200);

        // if($response->status!='ok') return response([
        //         'success' => false,
        //         'message' => 'status-dose-not-ok',
        //         'msg-code' => '333'
        // ] , 200);

        $conferenceLink = new ConferenceLink;

        $conferenceLink->ref_id = $conference->ref_id;
        $conferenceLink->ref_type = $conference->ref_type;
        $conferenceLink->order_id = $conference->order_id;
        $conferenceLink->conference_id = $conference->id;

        $conferenceLink->class_id = $conference->ref_id;
        $conferenceLink->user_name = $user->name;

        $conferenceLink->is_teacher = 1;

        $conferenceLink->user_id = $user->id;

        $conferenceLink->lesson_name = 'at ' . $conference->date . ' from ' . $conference->start_time . ' to ' . $conference->end_time;
        $conferenceLink->course_name = $conference->title;

        $conferenceLink->type = 'zoom'; //braincert
        $conferenceLink->status = 0;
        $conferenceLink->save();

        $postValues = array(
            'class_id' => $conferenceLink->class_id,
            'userId' => $conferenceLink->user_id,
            'userName' => $conferenceLink->user_name,
            'isTeacher' => $conferenceLink->is_teacher,
            'lessonName' => $conferenceLink->lesson_name,
            'courseName' => $conferenceLink->course_name
        );

        // $braincert = new BraincertController;
        // $conferenceLink->response = $braincert->conferenceLink($postValues);
        $conferenceLink->response = $conference->response;
        //$conferenceLink->notes = json_encode($postValues);
        $conferenceLink->save();

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $conferenceLink
        ], 200);
    }

    public function tutorCard($id)
    {
        $user = Auth::user();

        $conference = Conference::where('id', $id)->where('tutor_id', $user->id)->first();
        if (!$conference) return response([
            'success' => false,
            'message' => 'conference-not-exist',
            'msg-code' => '111'
        ], 200);

        return $this->card($conference, $user, 'tutor');
    }

    public function adminCard($id)
    {
        $user = Auth::user();

        $conference = Conference::where('id', $id)->first();
        if (!$conference) return response([
            'success' => false,
            'message' => 'conference-not-exist',
            'msg-code' => '111'
        ], 200);

        return $this->card($conference, $user, 'admin');
    }

    public function studentCard($id)
    {
        $user = Auth::user();

        $conference = Conference::where('id', $id)->whereRaw('((student_id=? and student_id<>0 and order_id<>0 and ref_type<>1) or (ref_id in (select class_id from group_class_students where student_id=?) and student_id=0 and order_id=0 and ref_type=1))', [$user->id, $user->id])->first();

        if (!$conference) return response([
            'success' => false,
            'message' => 'conference-dose-not-exist',
            'msg-code' => '111'
        ], 200);

        return $this->card($conference, $user, 'student');
    }

    public function card($conference, $user, $type)
    {

        if ($type == 'student') {
            $conference->files = $conference->files()->where('user_id', $user->id)->count();
            $conference->notes = $conference->notes()->where('user_id', $user->id)->count();
            $conference->links = $conference->links()->where('user_id', $user->id)->count();
        } else {
            $conference->files = $conference->files()->count();
            $conference->notes = $conference->notes()->count();
            $conference->links = $conference->links()->count();
        }

        $conference->reviews =  $conference->reviews()->get();
        if ($conference->reviews) foreach ($conference->reviews as $review) {
            $review->user;
            if ($review->user) $review->user->email = null;
        }
        $conference->rating =  $conference->reviews()->avg('stars');

        $conference->tutor;
        if ($conference->tutor) {
            $conference->tutor->abouts = $conference->tutor->abouts()->first();
            if ($type != 'admin') {
                $conference->tutor->email = null;
                $conference->tutor->email_verified_at = null;
            }
        }

        $conference->students = [];
        if ($conference->student_id > 0) {
            $conference->student;
            if ($conference->student) {
                if ($type != 'admin') {
                    $conference->student->email = null;
                    $conference->student->email_verified_at = null;
                }
                $conference->students = [$conference->student];
            }
        } else {
            $conference->student = null;
            $conference->students = GroupClassStudent::where('class_id', $conference->ref_id)->get();
        }

        if ($conference->ref_type == 1) {
            $conference->details = GroupClass::find($conference->ref_id);
            if ($conference->details) {
                $conference->details->langs = $conference->details->langs()->get();
                $conference->details->imageInfo;
            }
        }

        if ($conference->ref_type == 2) {
            $conference->details = OurCourse::find($conference->ref_id);
            if ($conference->details) {
                $conference->details->langs = $conference->details->langs()->get();
                $conference->details->imageInfo;
            }
        }

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $conference
        ], 200);
    }

    public function registerAttendance(Request $request, $id)
    {

        $user = Auth::user();

        $conference = Conference::where('id', $id)->first();
        if (!$conference) return response([
            'success' => false,
            'message' => 'conference-not-exist',
            'msg-code' => '111'
        ], 200);

        ConferenceAttendance::create([
            'ref_type' => $conference->ref_type,
            'ref_id' => $conference->ref_id,
            'conference_id' => $conference->id,
            'user_id' => $user->id
        ]);

        return response([
            'success' => true,
            'message' => 'attendance successfully',
        ], 200);
    }

    public function toggleConferenceAttendance(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'conference_id' => 'required|exists:conferences,id',
            'student_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 200);
        }

        $conference = Conference::where('id', $request->conference_id)->first();
        $conference_attendance = ConferenceAttendance::where(['conference_id' => $request->conference_id, 'user_id' => $request->student_id])->first();

        if ($conference_attendance) {
            $conference_attendance->status = ! $conference_attendance->status;
            $conference_attendance->save();
        } else {
            ConferenceAttendance::create([
                'ref_type' => $conference->ref_type,
                'ref_id' => $conference->ref_id,
                'conference_id' => $conference->id,
                'user_id' => $request->student_id,
                'status' => 1
            ]);
        }

        return response([
            'success' => true,
            'message' => 'attendance successfully',
        ], 200);
    }

    public function cancelConference(Request $request, $id)
    {
        $user = Auth::user();

        // السماح بالإلغاء فقط للطالب قبل 60 دقيقة من بدء الحصة
        $conference = Conference::where('id', $id)
            ->where('student_id', $user->id)
            ->whereRaw('TIMESTAMPDIFF(MINUTE, NOW(), `start_date_time`) > 60')
            ->whereIn('ref_type', [2, 4]) // private lessons only
            ->first();

        if (!$conference) {
            return response([
                'success' => false,
                'message' => 'Cancellation not allowed. Conference not found or less than 1 hour before start.',
                'msg-code' => '111'
            ], 200);
        }

        // حذف أو تعطيل الحصة
        $conference->cancelled = 1;
        $conference->save();

        // إرجاع الرسوم للطالب (إلى المحفظة)
        if ($conference->order_id) {
            $order = \App\Models\Order::find($conference->order_id);
            if ($order) {
                $wallet = $user->wallets()->first();
                if (!$wallet) {
                    $wallet = new \App\Models\UserWallet;
                    $wallet->user_id = $user->id;
                    $wallet->balance = $order->price;
                    $wallet->save();
                } else {
                    $wallet->balance += $order->price;
                    $wallet->save();
                }

                // حذف TutorFinance المرتبط
                TutorFinance::where('order_id', $order->id)->delete();
            }
        }

        // إرسال إشعار للمدرّس
        if ($conference->tutor_id) {
            $tutor = User::find($conference->tutor_id);
            if ($tutor && $tutor->fcm) {
                $info = [
                    'type' => 'lesson_cancelled',
                    'conference_id' => $conference->id
                ];
               sendFCMNotification(
    'Lesson Cancelled',
    'Student has cancelled the lesson scheduled for ' . $conference->date . ' at ' . $conference->start_time,
    $tutor->fcm,
    $info,
    $tutor->id
);

            }
        }

        return response([
            'success' => true,
            'message' => 'Conference cancelled successfully and amount refunded.',
        ], 200);
    }
}
