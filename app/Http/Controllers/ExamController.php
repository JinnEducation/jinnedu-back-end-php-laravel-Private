<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Exam;
use App\Models\ExamLang;
use App\Models\ExamQuestion;
use App\Models\ExamQuestionLang;
use App\Models\ExamAnswer;
use App\Models\ExamAnswerLang;
use App\Models\GroupClass;

class ExamController extends Controller
{
    /**
     * Get list of group classes that have exams
     */
    public function groupClassHasExams(Request $request)
    {
        $limit = setDataTablePerPageLimit($request->limit);

        $groupClasses = GroupClass::select('id', 'name')
            ->whereIn('id', function ($query) {
                $query->select('group_class_id')
                    ->from('exams')
                    ->distinct();
            })
            ->withCount(['exams as exams_count'])
            ->paginate($limit);

        return response([
            'success' => true,
            'message' => 'group-classes-with-exams-listed-successfully',
            'result' => $groupClasses
        ], 200);
    }

    /**
     * List all exams with pagination
     */
    public function index(Request $request)
    {
        $limit = setDataTablePerPageLimit($request->limit);
        
        $exams = Exam::with([
            'level:id,name',
            'category:id,name',
            'groupClass:id,name',
            'langsAll',
            'questions'
        ]);

        if ($request->group_class_id) {
            $exams = $exams->where('group_class_id', $request->group_class_id);
        }

        $exams = $exams->orderBy('id', 'desc')->paginate($limit);

        // Add questions count and total marks to each exam
        $exams->getCollection()->transform(function ($exam) {
            $exam->questions_count = $exam->questions->count();
            return $exam;
        });

        return response([
            'success' => true,
            'message' => 'exams-listed-successfully',
            'result' => $exams
        ], 200);
    }

    /**
     * Store a new exam with questions and answers
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'level_id' => 'required|exists:levels,id',
            'category_id' => 'required|exists:categories,id',
            'group_class_id' => 'required|exists:group_classes,id',
            'duration_minutes' => 'required|integer|min:1',
            'pass_percentage' => 'required|integer|min:0|max:100',
            'is_active' => 'boolean',
            'langs' => 'required|array|min:1',
            'langs.*.language_id' => 'required|exists:languages,id',
            'langs.*.title' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.question_no' => 'required|integer|min:1',
            'questions.*.type' => 'required|string|in:mcq,true_false,text',
            'questions.*.score' => 'required|integer|min:1',
            'questions.*.langs' => 'required|array|min:1',
            'questions.*.langs.*.language_id' => 'required|exists:languages,id',
            'questions.*.langs.*.title' => 'required|string',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*.is_correct' => 'required|boolean',
            'questions.*.answers.*.sort_order' => 'integer',
            'questions.*.answers.*.langs' => 'required|array|min:1',
            'questions.*.answers.*.langs.*.language_id' => 'required|exists:languages,id',
            'questions.*.answers.*.langs.*.title' => 'required|string',
        ];

        $messages = [
            'level_id.required' => 'level-required',
            'category_id.required' => 'category-required',
            'group_class_id.required' => 'group-class-required',
            'langs.required' => 'exam-langs-required',
            'questions.required' => 'questions-required',
            'questions.*.answers.min' => 'each-question-must-have-at-least-2-answers',
        ];

        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->first()
            ], 200);
        }

        \DB::beginTransaction();
        try {
            // Calculate total marks
            $total_marks = collect($request->questions)->sum('score');

            // Create exam
            $exam = Exam::create([
                'user_id' => $user->id,
                'level_id' => $request->level_id,
                'category_id' => $request->category_id,
                'group_class_id' => $request->group_class_id,
                'duration_minutes' => $request->duration_minutes,
                'pass_percentage' => $request->pass_percentage,
                'total_marks' => $total_marks,
                'is_active' => $request->is_active ?? true,
            ]);

            // Create exam translations
            foreach ($request->langs as $lang) {
                ExamLang::create([
                    'exam_id' => $exam->id,
                    'language_id' => $lang['language_id'],
                    'title' => $lang['title'],
                    'description' => $lang['description'] ?? null,
                    'instructions' => $lang['instructions'] ?? null,
                ]);
            }

            // Create questions with answers
            foreach ($request->questions as $questionData) {
                // Validate at least one correct answer
                $hasCorrectAnswer = collect($questionData['answers'])->contains('is_correct', true);
                if (!$hasCorrectAnswer) {
                    \DB::rollback();
                    return response()->json([
                        'success' => false,
                        'message' => 'question-' . $questionData['question_no'] . '-must-have-at-least-one-correct-answer'
                    ], 200);
                }

                $question = ExamQuestion::create([
                    'exam_id' => $exam->id,
                    'question_no' => $questionData['question_no'],
                    'type' => $questionData['type'],
                    'score' => $questionData['score'],
                    'is_active' => $questionData['is_active'] ?? true,
                ]);

                // Create question translations
                foreach ($questionData['langs'] as $lang) {
                    ExamQuestionLang::create([
                        'question_id' => $question->id,
                        'language_id' => $lang['language_id'],
                        'title' => $lang['title'],
                        'explanation' => $lang['explanation'] ?? null,
                    ]);
                }

                // Create answers
                foreach ($questionData['answers'] as $answerData) {
                    $answer = ExamAnswer::create([
                        'question_id' => $question->id,
                        'is_correct' => $answerData['is_correct'],
                        'sort_order' => $answerData['sort_order'] ?? 0,
                    ]);

                    // Create answer translations
                    foreach ($answerData['langs'] as $lang) {
                        ExamAnswerLang::create([
                            'answer_id' => $answer->id,
                            'language_id' => $lang['language_id'],
                            'title' => $lang['title'],
                        ]);
                    }
                }
            }

            // Load full exam with relations
            $exam = Exam::with([
                'level',
                'category',
                'groupClass',
                'langsAll',
                'questions.langsAll',
                'questions.answers.langsAll'
            ])->find($exam->id);

            \DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'exam-created-successfully',
                'data' => $exam
            ], 200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'something-wrong',
                'error' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * Show a single exam with all details
     */
    public function show(Request $request, $id)
    {
        $exam = Exam::with([
            'level',
            'category',
            'groupClass:id,name',
            'langsAll',
            'questions' => function ($query) {
                $query->orderBy('question_no');
            },
            'questions.langsAll',
            'questions.answers' => function ($query) {
                $query->orderBy('sort_order');
            },
            'questions.answers.langsAll'
        ])->find($id);

        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'exam-not-found'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'exam-retrieved-successfully',
            'data' => $exam
        ], 200);
    }

    /**
     * Update an existing exam
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $exam = Exam::find($id);
        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'exam-not-found'
            ], 200);
        }

        $rules = [
            'level_id' => 'required|exists:levels,id',
            'category_id' => 'required|exists:categories,id',
            'group_class_id' => 'required|exists:group_classes,id',
            'duration_minutes' => 'required|integer|min:1',
            'pass_percentage' => 'required|integer|min:0|max:100',
            'is_active' => 'boolean',
            'langs' => 'required|array|min:1',
            'langs.*.language_id' => 'required|exists:languages,id',
            'langs.*.title' => 'required|string',
            'questions' => 'required|array|min:1',
            'questions.*.question_no' => 'required|integer|min:1',
            'questions.*.type' => 'required|string|in:mcq,true_false,text',
            'questions.*.score' => 'required|integer|min:1',
            'questions.*.langs' => 'required|array|min:1',
            'questions.*.langs.*.language_id' => 'required|exists:languages,id',
            'questions.*.langs.*.title' => 'required|string',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*.is_correct' => 'required|boolean',
            'questions.*.answers.*.sort_order' => 'integer',
            'questions.*.answers.*.langs' => 'required|array|min:1',
            'questions.*.answers.*.langs.*.language_id' => 'required|exists:languages,id',
            'questions.*.answers.*.langs.*.title' => 'required|string',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->first()
            ], 200);
        }

        \DB::beginTransaction();
        try {
            // Calculate total marks
            $total_marks = collect($request->questions)->sum('score');

            // Update exam
            $exam->update([
                'level_id' => $request->level_id,
                'category_id' => $request->category_id,
                'group_class_id' => $request->group_class_id,
                'duration_minutes' => $request->duration_minutes,
                'pass_percentage' => $request->pass_percentage,
                'total_marks' => $total_marks,
                'is_active' => $request->is_active ?? true,
            ]);

            // Delete old translations and questions (cascade will handle answers)
            ExamLang::where('exam_id', $exam->id)->delete();
            ExamQuestion::where('exam_id', $exam->id)->delete();

            // Create exam translations
            foreach ($request->langs as $lang) {
                ExamLang::create([
                    'exam_id' => $exam->id,
                    'language_id' => $lang['language_id'],
                    'title' => $lang['title'],
                    'description' => $lang['description'] ?? null,
                    'instructions' => $lang['instructions'] ?? null,
                ]);
            }

            // Create questions with answers
            foreach ($request->questions as $questionData) {
                // Validate at least one correct answer
                $hasCorrectAnswer = collect($questionData['answers'])->contains('is_correct', true);
                if (!$hasCorrectAnswer) {
                    \DB::rollback();
                    return response()->json([
                        'success' => false,
                        'message' => 'question-' . $questionData['question_no'] . '-must-have-at-least-one-correct-answer'
                    ], 200);
                }

                $question = ExamQuestion::create([
                    'exam_id' => $exam->id,
                    'question_no' => $questionData['question_no'],
                    'type' => $questionData['type'],
                    'score' => $questionData['score'],
                    'is_active' => $questionData['is_active'] ?? true,
                ]);

                // Create question translations
                foreach ($questionData['langs'] as $lang) {
                    ExamQuestionLang::create([
                        'question_id' => $question->id,
                        'language_id' => $lang['language_id'],
                        'title' => $lang['title'],
                        'explanation' => $lang['explanation'] ?? null,
                    ]);
                }

                // Create answers
                foreach ($questionData['answers'] as $answerData) {
                    $answer = ExamAnswer::create([
                        'question_id' => $question->id,
                        'is_correct' => $answerData['is_correct'],
                        'sort_order' => $answerData['sort_order'] ?? 0,
                    ]);

                    // Create answer translations
                    foreach ($answerData['langs'] as $lang) {
                        ExamAnswerLang::create([
                            'answer_id' => $answer->id,
                            'language_id' => $lang['language_id'],
                            'title' => $lang['title'],
                        ]);
                    }
                }
            }

            // Load full exam with relations
            $exam = Exam::with([
                'level',
                'category',
                'groupClass',
                'langsAll',
                'questions.langsAll',
                'questions.answers.langsAll'
            ])->find($exam->id);

            \DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'exam-updated-successfully',
                'data' => $exam
            ], 200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'something-wrong',
                'error' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * Delete an exam
     */
    public function destroy($id)
    {
        $exam = Exam::find($id);
        
        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'exam-not-found'
            ], 200);
        }

        \DB::beginTransaction();
        try {
            // Cascade delete will handle all related records
            $exam->delete();

            \DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'exam-deleted-successfully'
            ], 200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'something-wrong',
                'error' => $e->getMessage()
            ], 200);
        }
    }
}
