<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GroupClassController;
use App\Models\GroupClass;
use App\Models\GroupClassTutor;
use App\Models\Language;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamAttemptAnswer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request, string $locale, $group_class_id)
    {
        $group_class = GroupClass::with([
            'level',
            'category',
            'category.langsAll',
            'langsAll.language',
            'dates',
            'reviews.user',
            'tutor',
            'tutor.hourlyPrices',
            'tutor.reviews',
            'tutor.descriptions',
            'tutor.abouts.language',
            'tutor.abouts.subject',
            'tutor.videos',
            'imageInfo',
            'attachment',
        ])->find($group_class_id);

        if (!$group_class) {
            abort(404, 'Group class not found');
        }

        // Get the exam for this group class with all questions and answers
        $exam = Exam::with([
            'langsAll',
            'questions' => function ($query) {
                $query->where('is_active', true)->orderBy('question_no');
            },
            'questions.langsAll',
            'questions.answers' => function ($query) {
                $query->orderBy('sort_order');
            },
            'questions.answers.langsAll'
        ])->where('group_class_id', $group_class_id)
          ->where('is_active', true)
          ->first();

        if (!$exam) {
            abort(404, 'No active exam found for this class');
        }

        $lang_id = Language::where('shortname', $locale)->first()->id;

        return view('front.exam.take_exam', compact('exam', 'group_class', 'group_class_id', 'lang_id'));
    }

    public function store(Request $request, string $locale, $group_class_id)
    {
        // dd($request->all());
        $user = \Auth::user();
        
        if (!$user) {
            return redirect()->route('login', ['locale' => $locale]);
        }

        $questions = $request->questions;

        if (!$questions || !is_array($questions)) {
            return back()->with('error', 'No answers submitted');
        }

        // Get the exam
        $exam = Exam::with([
            'questions.answers'
        ])->where('group_class_id', $group_class_id)
          ->where('is_active', true)
          ->first();

        if (!$exam) {
            return back()->with('error', 'Exam not found');
        }

        $time_elapsed = $request->time_elapsed;
        $time_exam = $exam->duration_minutes * 60;

        // Calculate attempt number for this student
        $attempt_no = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', $user->id)
            ->count() + 1;

        // Create exam attempt
        $attempt = ExamAttempt::create([
            'exam_id' => $exam->id,
            'class_id' => $group_class_id,
            'student_id' => $user->id,
            'attempt_no' => $attempt_no,
            'started_at' => Carbon::now()->subSeconds($time_exam - $time_elapsed),
            'finished_at' => Carbon::now(),
        ]);

        $total_questions = $exam->questions->count();
        $correct_answers = 0;
        $total_score = 0;

        // Process each answer
        foreach ($questions as $question_id => $answer_id) {
            $question = $exam->questions->where('id', $question_id)->first();
            
            if (!$question) {
                continue;
            }

            $selected_answer = $question->answers->where('id', $answer_id)->first();
            $is_correct = $selected_answer ? $selected_answer->is_correct : false;

            if ($is_correct) {
                $correct_answers++;
                $total_score += $question->score;
            }

            // Store the attempt answer
            ExamAttemptAnswer::create([
                'attempt_id' => $attempt->id,
                'question_id' => $question_id,
                'answer_id' => $answer_id,
                'is_correct' => $is_correct,
                'time_spent_sec' => 0, // Can be calculated from frontend if needed
            ]);
        }

        // Calculate success rate
        $success_rate = $total_questions > 0 ? round(($correct_answers / $total_questions) * 100) : 0;
        $result = $success_rate >= $exam->pass_percentage ? 'passed' : 'failed';

        // Update attempt with results
        $attempt->update([
            'success_rate' => $success_rate,
            'result' => $result,
            'total_score' => $total_score,
        ]);

        if($result == 'passed') {
            return redirect()->route('site.take_exam_successful', [
                'locale' => $locale,
                'id' => $attempt->id
            ]);
        } else {
            return redirect()->route('site.exam_result', [
                'locale' => $locale,
                'id' => $attempt->id
            ]);
        }
    }

    public function show(Request $request, string $locale, $id)
    {
        $user = \Auth::user();
        
        // Get the attempt with all related data
        $attempt = ExamAttempt::with([
            'exam.langsAll',
            'exam.groupClass',
            'answers.question.langsAll',
            'answers.answer.langsAll',
            'student',
            'student.profile'
        ])->where('id', $id)
          ->where('student_id', $user->id)
          ->first();

        if (!$attempt) {
            abort(404, 'Exam result not found');
        }

        $lang_id = Language::where('shortname', $locale)->first()->id;

        // Suggestions for other classes
        $now = Carbon::now();
        $groupClassController = new GroupClassController;
        $request = new Request;
        $request->limit = 1000;
        $response = $groupClassController->getAssignedGroupClass($request, null);
        $original = $response->getOriginalContent();

        $suggestions = collect($original['result']->items());

        $groupClass = $attempt->exam->groupClass;
        $suggestions = $suggestions->filter(function ($class) use ($now, $id, $groupClass) {

            if ($class->id == $id) {
                return false;
            }

            // 1. التحقق من أن الكلاس فعال (status = 1)
            if ($class->status != 1) {
                return false;
            }

            // 2. التحقق من وجود معلم مرتبط بالكلاس
            if (! $class->tutor_id) {
                return false;
            }

            // 3. التحقق من أن المعلم موافق عليه في group_class_tutors
            $tutorApproved = GroupClassTutor::where('group_class_id', $class->id)
                ->where('tutor_id', $class->tutor_id)
                ->where('status', 'approved')
                ->exists();

            if (! $tutorApproved) {
                return false;
            }

            // 4. التحقق من أن جميع الحصص لم تنتهي (class_date > now)
            if ($class->dates->isEmpty()) {
                return false;
            }

            // تحقق إذا الحصة اليوم أو إذا فقط في حصة واحدة، باقي الحصص لازم تكون بعد الآن أو اليوم نفسه
            $allSessionsValid = $class->dates->every(function ($date) use ($now) {
                $dateObj = Carbon::parse($date->class_date);

                // إذا الحصة اليوم تعتبر مقبولة أيضاً
                return $dateObj->isAfter($now) || $dateObj->isSameDay($now);
            });

            // إذا يوجد فقط حصة واحدة نسمح بعرضها حتى ولو كانت اليوم
            if ($class->dates->count() == 1) {
                $allSessionsValid = true;
            }

            if (! $allSessionsValid) {
                return false;
            }

            if($class->level?->level_number <= $groupClass->level?->level_number){
                if($class->exams?->count() <= 0){
                    return false;
                }
            }

            return true;
        });
        return view('front.exam.exam_result', compact('attempt', 'lang_id', 'suggestions'));
    }

    public function success(Request $request, string $locale, $id)
    {
        $user = \Auth::user();
        
        $attempt = ExamAttempt::with([
            'exam.langsAll',
            'exam.groupClass',
            'student'
        ])->where('id', $id)
          ->where('student_id', $user->id)
          ->first();

        if (!$attempt) {
            abort(404, 'Exam result not found');
        }

        $lang_id = Language::where('shortname', $locale)->first()->id;

        return view('front.exam.take_exam_sucssful', compact('attempt', 'lang_id'));
    }
}
