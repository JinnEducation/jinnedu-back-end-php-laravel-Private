<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAnswerLang extends Model
{
    protected $table = 'exam_answer_langs';
    protected $guarded = [];

    public function answer()
    {
        return $this->belongsTo(ExamAnswer::class, 'answer_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}

