<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAttemptAnswer extends Model
{
    protected $table = 'exam_attempt_answers';
    protected $guarded = [];

    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(ExamQuestion::class, 'question_id');
    }

    public function answer()
    {
        return $this->belongsTo(ExamAnswer::class, 'answer_id');
    }
}

