<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamQuestionLang extends Model
{
    protected $table = 'exam_question_langs';
    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(ExamQuestion::class, 'question_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}

