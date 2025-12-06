<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ExamAnswer extends Model
{
    protected $table = 'exam_answers';
    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(ExamQuestion::class, 'question_id');
    }

    public function attemptAnswers()
    {
        return $this->hasMany(ExamAttemptAnswer::class, 'answer_id');
    }

    // Translation relations
    public function langs()
    {
        $lang = Request::header('lang');
        $language = Language::where('shortname', $lang)->first();

        return $language ? 
                $this->hasMany(ExamAnswerLang::class, 'answer_id')->where('language_id', $language->id) 
                : $this->hasMany(ExamAnswerLang::class, 'answer_id');
    }

    public function langsAll()
    {
        return $this->hasMany(ExamAnswerLang::class, 'answer_id');
    }
}

