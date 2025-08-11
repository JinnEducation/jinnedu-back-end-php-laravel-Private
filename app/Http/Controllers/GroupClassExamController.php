<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DateTime;
use App\Models\User;
use App\Models\Exam;
use App\Models\GroupClass;
use App\Models\ExamLang;
use App\Models\Answer;
use App\Models\AnswerLang;
use App\Models\GroupClassExam;
use App\Models\GroupClassQuestion;
use App\Models\GroupClassQuestionLang;
use App\Models\GroupClassAnswer;
use App\Models\GroupClassAnswerLang;
use Bouncer;
use Mail;
use App\Models\Setting;

class GroupClassExamController extends Controller
{
    public function index(Request $request,$id){

        $group_class = GroupClass::where('id',$id)->first();
        if(!$group_class){
            if(!$group_class) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
            ] , 200);
        }

        // $questions_no = 10;
        
        // $setting_questions = Setting::where('name','questions_no')->first();
        // if($setting_questions){
        //     $questions_no = $setting_questions->value;
        // }
        
        
        $emaxs = Exam::with(['level','category','langs','answers.langs'])
                // ->where('level_id',$group_class->level_id)
                ->where('group_class_id',$group_class->id)
                ->get();//->inRandomOrder()->limit($questions_no)


        return response()->json(['success' => true, 'message' => 'item-added-successfully','result' => $emaxs],200);
    }

    public function store(Request $request){
        $user = \Auth::user();
        $data = $request;
        $answers = $data['answers'];
        $class_id = $data['class_id'];

        $rules = [
            'class_id' => 'required',
            'answers.*' => 'required',
        ];

        $messages = [
            'class_id.required' => 'group-class-required',
            'answers.*.required' => 'answers-required',
        ];

        $validator = \Validator::make([
            'class_id' => $class_id,
            'answers' => $answers,
        ],
            $rules
            ,
            $messages
        );

        if ($validator->fails()) {
            return response()->json(['success' => false , 'message' => $validator->messages()->first()],200);
        }


        $group_class = GroupClass::where('id',$class_id)->first();
        if(!$group_class){
            if(!$group_class) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
            ] , 200);
        }

        // $questions_no = 10;
        
        // $setting_questions = Setting::where('name','questions_no')->first();
        // if($setting_questions){
        //     $questions_no = $setting_questions->value;
        // }   

        $questions_no = Exam::where('group_class_id', $group_class->id)->count();
        $success_rate = 60;

        $setting_success_rate = Setting::where('name','success_rate')->first();
        if($setting_success_rate){
            $success_rate = $setting_success_rate->value;
        }  

        if(count($answers) != $questions_no){
            return response()->json(['success' => false , 'message' => 'check-answers-number'],200);
        }

        \DB::beginTransaction();
        try {

            $total_score = 0;

            $group_class_exam = new GroupClassExam();
            $group_class_exam->class_id = $group_class->id;
            $group_class_exam->student_id = $user->id;
            $group_class_exam->question_no = $questions_no;
            $group_class_exam->success_rate = $success_rate;
            $group_class_exam->save();
            
            if($answers){
                foreach($answers as $answer){
                    $question_id = $answer['question_id'];
                    $answer_id = $answer['answer_id'];

                    $exam = Exam::with('langs','answers.langs')->where('id',$question_id)->first();
                    if(!$exam){
                        return response([
                            'success' => false,
                            'message' => 'item-dose-not-exist',
                            'msg-code' => '111'
                        ] , 200);
                    }

                    $group_class_question = new GroupClassQuestion();
                    $group_class_question->class_id = $group_class->id;
                    $group_class_question->exam_id = $question_id;
                    $group_class_question->group_class_exam_id = $group_class_exam->id;
                    $group_class_question->save();
                    
                    if($exam->langs){
                        foreach($exam->langs as $langs){
                            $group_class_question_langs = new GroupClassQuestionLang();
                            $group_class_question_langs->language_id = $langs->language_id;
                            $group_class_question_langs->title = $langs->title;
                            $group_class_question_langs->group_class_question_id = $group_class_question->id;
                            $group_class_question_langs->group_class_exam_id = $group_class_exam->id;
                            $group_class_question_langs->save();
                        }
                    }
                   
                    if($exam->answers){
                        foreach($exam->answers as $ex_answer){
                            $group_class_answer = new GroupClassAnswer();
                            $group_class_answer->is_true =  $ex_answer->is_true; 
                            $group_class_answer->group_class_question_id =  $group_class_question->id;
                            $group_class_answer->class_id =  $group_class->id;
                            $group_class_answer->group_class_exam_id = $group_class_exam->id;
                            if($ex_answer->id == $answer_id){
                                $group_class_answer->student_answer = 1;
                            }else{
                                $group_class_answer->student_answer = 0;
                            }
                            $group_class_answer->save();

                            if($ex_answer->is_true == 1 and $ex_answer->id == $answer_id){
                                $total_score = $total_score + 1;
                            }
                            
                            if($ex_answer->langs){
                                foreach($ex_answer->langs as $answ_lang){
                                    $group_class_answer_lang = new GroupClassAnswerLang();
                                    $group_class_answer_lang->title = $answ_lang->title;
                                    $group_class_answer_lang->language_id = $answ_lang->language_id;
                                    $group_class_answer_lang->group_class_exam_id = $group_class_exam->id;
                                    $group_class_answer_lang->group_class_answer_id = $group_class_answer->id;
                                    $group_class_answer_lang->save();
                                }
                            }
                        }
                    }   
                }
            }

            $total_score = ($total_score/$questions_no)*100;

            $group_class_exam->result =  $total_score;
            $group_class_exam->save();
            if($total_score >= $success_rate){
                $msg = 'you-passed-exam';
            }else{
                $msg = 'you-failed-exam';
            }
            
            $data = GroupClassExam::with('exam.langs','exam.answers','exam.answers.langs')
                    ->where('id',$group_class_exam->id)->first();

            $suggestions = array();

            if($total_score < $success_rate){
                $suggestions =  GroupClass::with(['langs','category.langs','dates','imageInfo','tutor'])
                            ->Where('id','!=',$group_class->id)
                            ->where('level_id','<',$group_class->level_id)
                            ->where('category_id',$group_class->category_id)
                            ->orderBy('id','desc')->take(6)->get();
            }
            
            \DB::commit();
        return response()->json(['success' => true, 'message' => $msg, 'result'=>['data'=>$data,'suggestions'=>$suggestions]],200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['success' => false , 'message' => 'something-wrong','e'=>$e->getMessage()],200);
        }
        
    }
    
    public function getGroupClass($id){
        $user = \Auth::user();
         $data = GroupClassExam::with('exam.langs','exam.answers','exam.answers.langs')
                    ->where('id',$id)->where('student_id',$user->id)->first();
                    if(!$data){
                          return response()->json(['success' => false , 'message' => 'item-not-found']);
                    }
                    
                       $suggestions = array();

            if($data->is_passed == 0){
                $group_class= GroupClass::find($data->class_id);
                $suggestions =  GroupClass::with(['langs','category.langs','dates','imageInfo','tutor'])
                            ->Where('id','!=',$group_class->id)
                            ->where('level_id','<',$group_class->level_id)
                            ->where('category_id',$group_class->category_id)
                            ->orderBy('id','desc')->take(6)->get();
            }
            
           return response()->json(['success' => true, 'message' => 'item-added-successfully','data'=>$data,'suggestions'=>$suggestions],200);
          
                  
    }

}
