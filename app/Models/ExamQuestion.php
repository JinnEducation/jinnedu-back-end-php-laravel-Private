<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ExamQuestion extends Model
{
    protected $table = 'exam_questions';
    protected $guarded = [];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class, 'question_id');
    }

    public function attemptAnswers()
    {
        return $this->hasMany(ExamAttemptAnswer::class, 'question_id');
    }

    // Translation relations
    public function langs()
    {
        $lang = Request::header('lang');
        $language = Language::where('shortname', $lang)->first();

        return $language ? 
                $this->hasMany(ExamQuestionLang::class, 'question_id')->where('language_id', $language->id) 
                : $this->hasMany(ExamQuestionLang::class, 'question_id');
    }

    public function langsAll()
    {
        return $this->hasMany(ExamQuestionLang::class, 'question_id');
    }
}

