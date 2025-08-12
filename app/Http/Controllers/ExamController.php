<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DateTime;
use App\Models\User;
use App\Models\Exam;
use App\Models\ExamLang;
use App\Models\Answer;
use App\Models\AnswerLang;
use App\Models\GroupClass;
use Bouncer;
use Mail;

class ExamController extends Controller
{

    public function groupClassHasExams(Request $request)
    {
        $limit = setDataTablePerPageLimit($request->limit);

        $groupClasses = GroupClass::select('id', 'name')
            ->whereIn('id', function ($query) {
                $query->select('group_class_id')
                    ->from('exams')
                    ->distinct();
            })
            ->withCount(['exams as questions_count' => function ($query) {
                $query->selectRaw('count(*)');
            }])
            ->paginate($limit);

        return response([
            'success' => true,
            'message' => 'group-classes-with-questions-listed-successfully',
            'result' => $groupClasses
        ], 200);
    }

    public function index(Request $request){
        $limit = setDataTablePerPageLimit($request->limit);
        $exams = Exam::with(['level','category','langs', 'group_class:id,name']);

        if($request->group_class_id){
            $exams = $exams->where('group_class_id',$request->group_class_id);
        }

        $exams = $exams->orderBy('id','desc')->paginate($limit);

        return response([
                        'success' => true,
                        'message' => 'item-listed-successfully',
                        'result' => $exams
                ] , 200);
    }

    // public function store(Request $request){
    //     $user = \Auth::user();
    //     $data = $request;
    //     $level_id = $data['level_id'];
    //     $category_id = $data['category_id'];
    //     $group_class_id = $data['group_class_id'];
    //     $langs = $data['langs'];
    //     $answers = $data['answers'];

    //     if(count($answers) < 2){
    //         return response()->json(['success' => false , 'message' => 'answers-number-2'],200);
    //     }

    //     $rules = [
    //         'level_id' => 'required',
    //         'category_id' => 'required',
    //         'group_class_id' => 'required',
    //         'langs.*' => 'required',
    //         'answers.*' => 'required',
    //     ];

    //     $messages = [
    //         'level_id.required' => 'level-required',
    //         'category_id.required' => 'category-required',
    //         'group_class_id.required' => 'group_class-required',
    //         'langs.*.required' => 'langs-required',
    //         'answers.*.required' => 'answers-required',
    //     ];

    //     $validator = \Validator::make([
    //         'level_id' => $level_id,
    //         'category_id' => $category_id,
    //         'group_class_id' => $group_class_id,
    //         'langs' => $langs,
    //         'answers' => $answers,
    //     ],
    //         $rules
    //         ,
    //         $messages
    //     );

    //     if ($validator->fails()) {
    //         return response()->json(['success' => false , 'message' => $validator->messages()->first()],200);
    //     }

    //     \DB::beginTransaction();
    //     try {

    //         $exam = new Exam();
    //         $exam->user_id = $user->id;
    //         $exam->category_id = $category_id;
    //         $exam->level_id = $level_id;
    //         $exam->group_class_id = $group_class_id;
    //         $saved = $exam->save();
    //         if(!$saved){
    //             return response()->json(['success' => false , 'message' => 'something-wrong'],200);
    //         }   

    //         if($langs){
    //             foreach($langs as $key=>$lang){
    //                 $exam_lang = new ExamLang();
    //                 $exam_lang->language_id = $lang['language_id'];
    //                 $exam_lang->title = $lang['title'];
    //                 $exam_lang->exam_id = $exam->id;
    //                 $exam_lang->save();
    //             }
    //         }   
            
           

    //         if($answers){
    //             foreach($answers as $answer){
                    
    //                 $exam_answer = new Answer();
    //                 $exam_answer->user_id = $user->id;
    //                 $exam_answer->exam_id = $exam->id;
    //                 $exam_answer->is_true = $answer['is_true'];
    //                 $exam_answer->save();
                    
    //                 if($answer['langs']){
    //                     foreach($answer['langs'] as $ans_lang){
    //                         $answer_lang = new AnswerLang();
    //                         $answer_lang->language_id = $ans_lang['language_id'];
    //                         $answer_lang->title = $ans_lang['title'];
    //                         $answer_lang->exam_id =  $exam->id;
    //                         $answer_lang->answer_id = $exam_answer->id;
    //                         $answer_lang->save();
    //                     }
    //                 }
    //             }
    //         }


    //         $exam = Exam::with(['level','category','group_class','langs','answers.langs'])->where('id',$exam->id)->first();

    //     \DB::commit();
    //     return response()->json(['success' => true, 'message' => 'item-added-successfully','data'=>$exam],200);
    //     } catch (\Exception $e) {
    //         \DB::rollback();
    //         return response()->json(['success' => false , 'message' => 'something-wrong','e'=>$e->getMessage()],200);
    //     }

    // }

    public function store(Request $request){
        $user = \Auth::user();

        $rules = [
            'level_id' => 'required',
            'category_id' => 'required',
            'group_class_id' => 'required',
            'questions' => 'required',
            'questions.*.langs' => 'required',
            'questions.*.answers' => 'required',
        ];

        $messages = [
            'level_id.required' => 'level-required',
            'category_id.required' => 'category-required',
            'group_class_id.required' => 'group_class-required',
            'questions.required' => 'questions-required',
            'questions.*.langs.required' => 'question-:index-langs-required',
            'questions.*.answers.required' => 'question-:index-answers-required',
        ];

        $validator = \Validator::make($request->input(),
            $rules
            ,
            $messages
        );

        if ($validator->fails()) {
            return response()->json(['success' => false , 'message' => $validator->messages()->first()],200);
        }

        \DB::beginTransaction();
        try {

            foreach($request->questions as $key=>$question){
                
                if(count($question['answers']) < 2){
                    return response()->json(['success' => false , 'message' => 'answers of question '. ($key+1) .' must be at least 2 answers'],200);
                }

                $exam = new Exam();
                $exam->user_id = $user->id;
                $exam->category_id = $request->category_id;
                $exam->level_id = $request->level_id;
                $exam->group_class_id = $request->group_class_id;
                $saved = $exam->save();
                if(!$saved){
                    return response()->json(['success' => false , 'message' => 'something-wrong'],200);
                }   
    
            
                foreach($question['langs'] as $key=>$lang){
                    $exam_lang = new ExamLang();
                    $exam_lang->language_id = $lang['language_id'];
                    $exam_lang->title = $lang['title'];
                    $exam_lang->exam_id = $exam->id;
                    $exam_lang->save();
                }  
    

                foreach($question['answers'] as $answer){
                    
                    $exam_answer = new Answer();
                    $exam_answer->user_id = $user->id;
                    $exam_answer->exam_id = $exam->id;
                    $exam_answer->is_true = $answer['is_true'];
                    $exam_answer->save();
                    
                    if($answer['langs']){
                        foreach($answer['langs'] as $ans_lang){
                            $answer_lang = new AnswerLang();
                            $answer_lang->language_id = $ans_lang['language_id'];
                            $answer_lang->title = $ans_lang['title'];
                            $answer_lang->exam_id =  $exam->id;
                            $answer_lang->answer_id = $exam_answer->id;
                            $answer_lang->save();
                        }
                    }
                }
 
            }

            $exam = Exam::with(['level','category','group_class','langs','answers.langs'])->where('id',$exam->id)->first();

        \DB::commit();
        return response()->json(['success' => true, 'message' => 'item-added-successfully','data'=>$exam],200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['success' => false , 'message' => 'something-wrong','e'=>$e->getMessage()],200);
        }

    }

    public function show(Request $request,$id){
        $exam = Exam::with(['level', 'group_class:id,name','category','langs','answers.langs'])->where('id',$id)->first();
        if(!$exam){
            return response()->json(['success' => false , 'message' => 'item-dose-not-exist'],200);
        }
        return response()->json(['success' => true, 'message' => '','data'=>$exam],200);
    }

    // public function update(Request $request,$id){
    //     $user = \Auth::user();
    //     $data = $request;
    //     $level_id = $data['level_id'];
    //     $category_id = $data['category_id'];
    //     $group_class_id = $data['group_class_id'];
    //     $langs = $data['langs'];
    //     $answers = $data['answers'];


    //     if(count($answers) != 2){
    //         return response()->json(['success' => false , 'message' => 'answers-number-2'],200);
    //     }
        

    //     $exam = Exam::with(['level','category','group_class','langs'])->where('id',$id)->first();
    //     if(!$exam){
    //         return response()->json(['success' => false , 'message' => 'item-dose-not-exist'],200);
    //     }

        
    //     $rules = [
    //         'level_id' => 'required',
    //         'category_id' => 'required',
    //         'group_class_id' => 'required',
    //         'langs.*' => 'required',
    //         'answers.*' => 'required',
    //     ];

    //     $messages = [
    //         'level_id.required' => 'level-required',
    //         'category_id.required' => 'category-required',
    //         'group_class_id.required' => 'group_class-required',
    //         'langs.*.required' => 'langs-required',
    //         'answers.*.required' => 'answers-required',
    //     ];

    //     $validator = \Validator::make([
    //         'level_id' => $level_id,
    //         'category_id' => $category_id,
    //         'group_class_id' => $group_class_id,
    //         'langs' => $langs,
    //         'answers' => $answers,
    //     ],
    //         $rules
    //         ,
    //         $messages
    //     );

    //     if ($validator->fails()) {
    //         return response()->json(['success' => false , 'message' => $validator->messages()->first()],200);
    //     }

    //     \DB::beginTransaction();
    //     try {
            
            
    //         $exam->category_id = $category_id;
    //         $exam->group_class_id = $group_class_id;
    //         $exam->level_id = $level_id;
    //         $saved = $exam->save();
    //         if(!$saved){
    //             return response()->json(['success' => false , 'message' => 'something-wrong'],200);
    //         }   

    //         ExamLang::where('exam_id',$exam->id)->delete();

    //         if($langs){
    //             foreach($langs as $key=>$lang){
    //                 $exam_lang = new ExamLang();
    //                 $exam_lang->language_id = $lang['language_id'];
    //                 $exam_lang->title = $lang['title'];
    //                 $exam_lang->exam_id = $exam->id;
    //                 $exam_lang->save();
    //             }
    //         }


    //         if($answers){
    //             foreach($answers as $answer){
    //                 if($answer['id'] == 0){
    //                     $exam_answer = new Answer();
    //                     $exam_answer->user_id = $user->id;
    //                     $exam_answer->exam_id = $exam->id;
    //                 }else{
    //                     $exam_answer = Answer::find($answer['id']);
    //                     if(!$exam_answer){
    //                         return response()->json(['success' => false , 'message' => 'answer-not-found'],200);
    //                     }
    //                 }
    //                 $exam_answer->is_true = $answer['is_true'];
    //                 $exam_answer->save();
                    
    //                 if($answer['id'] != 0){
    //                     AnswerLang::where('answer_id',$exam_answer->id)->delete();
    //                 }
                    
    //                 if($answer['langs']){
    //                     foreach($answer['langs'] as $ans_lang){
    //                         $answer_lang = new AnswerLang();
    //                         $answer_lang->language_id = $ans_lang['language_id'];
    //                         $answer_lang->title = $ans_lang['title'];
    //                         $answer_lang->exam_id =  $exam->id;
    //                         $answer_lang->answer_id = $exam_answer->id;
    //                         $answer_lang->save();
    //                     }
    //                 }
    //             }
    //         }

    //         $exam = Exam::with(['level','category','group_class','langs','answers.langs'])->where('id',$exam->id)->first();

    //         \DB::commit();
    //     return response()->json(['success' => true, 'message' => 'item-updated-successfully','data'=>$exam],200);
    //     } catch (\Exception $e) {
    //         \DB::rollback();
    //         return response()->json(['success' => false , 'message' => 'something-wrong','e'=>$e->getMessage()],200);
    //     }
    // }

    public function update(Request $request,$id){
        $user = \Auth::user();

        $exam = Exam::with(['level','category','group_class','langs'])->where('id',$id)->first();
        if(!$exam){
            return response()->json(['success' => false , 'message' => 'item-dose-not-exist'],200);
        }

        $rules = [
            'level_id' => 'required',
            'category_id' => 'required',
            'group_class_id' => 'required',
            'questions' => 'required',
            'questions.*.langs' => 'required',
            'questions.*.answers' => 'required',
        ];

        $messages = [
            'level_id.required' => 'level-required',
            'category_id.required' => 'category-required',
            'group_class_id.required' => 'group_class-required',
            'questions.required' => 'questions-required',
            'questions.*.langs.required' => 'question-:index-langs-required',
            'questions.*.answers.required' => 'question-:index-answers-required',
        ];

        $validator = \Validator::make($request->input(),
            $rules
            ,
            $messages
        );

        if ($validator->fails()) {
            return response()->json(['success' => false , 'message' => $validator->messages()->first()],200);
        }



        \DB::beginTransaction();
        try {
            
            $exam->category_id = $request->category_id;
            $exam->group_class_id = $request->group_class_id;
            $exam->level_id = $request->level_id;
            $saved = $exam->save();
            if(!$saved){
                return response()->json(['success' => false , 'message' => 'something-wrong'],200);
            }   

            ExamLang::where('exam_id',$exam->id)->delete();
            
            foreach($request->questions as $key=>$question){
                
                if(count($question['answers']) < 2){
                    return response()->json(['success' => false , 'message' => 'answers of question '. ($key+1) .' must be at least 2 answers'],200);
                }
    
                foreach($question['langs'] as $key=>$lang){
                    $exam_lang = new ExamLang();
                    $exam_lang->language_id = $lang['language_id'];
                    $exam_lang->title = $lang['title'];
                    $exam_lang->exam_id = $exam->id;
                    $exam_lang->save();
                }  
    

                foreach($question['answers'] as $answer){
                    
                    if($answer['id'] == 0){
                        $exam_answer = new Answer();
                        $exam_answer->user_id = $user->id;
                        $exam_answer->exam_id = $exam->id;
                    }else{
                        $exam_answer = Answer::find($answer['id']);
                        if(!$exam_answer){
                            return response()->json(['success' => false , 'message' => 'answer-not-found'],200);
                        }
                    }
                    $exam_answer->is_true = $answer['is_true'];
                    $exam_answer->save();
                    
                    if($answer['langs']){
                        foreach($answer['langs'] as $ans_lang){
                            $answer_lang = new AnswerLang();
                            $answer_lang->language_id = $ans_lang['language_id'];
                            $answer_lang->title = $ans_lang['title'];
                            $answer_lang->exam_id =  $exam->id;
                            $answer_lang->answer_id = $exam_answer->id;
                            $answer_lang->save();
                        }
                    }
                }
 
            }

            $exam = Exam::with(['level','category','group_class','langs','answers.langs'])->where('id',$exam->id)->first();

            \DB::commit();
        return response()->json(['success' => true, 'message' => 'item-updated-successfully','data'=>$exam],200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['success' => false , 'message' => 'something-wrong','e'=>$e->getMessage()],200);
        }
    }


    public function destroy($id){
        $exam = Exam::where('id',$id)->first();
        if(!$exam){
            return response()->json(['success' => false , 'message' => 'item-dose-not-exist'],200);
        }

        \DB::beginTransaction();
        try {

            Answer::where('exam_id',$exam->id)->delete();
            AnswerLang::where('exam_id',$exam->id)->delete();
            ExamLang::where('exam_id',$exam->id)->delete();
            $exam->delete();

            \DB::commit();
        return response()->json(['success' => true, 'message' => 'item-deleted-successfully'],200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['success' => false , 'message' => 'something-wrong','e'=>$e->getMessage()],200);
        }
    }
   
}