<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Exam extends Model
{
    protected $table = 'exams';
    protected $guarded = [];

    // Relations to core entities
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function groupClass()
    {
        return $this->belongsTo(GroupClass::class, 'group_class_id');
    }

    // Relations to exam structure
    public function questions()
    {
        return $this->hasMany(ExamQuestion::class, 'exam_id');
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class, 'exam_id');
    }

    // Translation relations
    public function langs()
    {
        $lang = Request::header('lang');
        $language = Language::where('shortname', $lang)->first();

        return $language ? 
                $this->hasMany(ExamLang::class, 'exam_id')->where('language_id', $language->id) 
                : $this->hasMany(ExamLang::class, 'exam_id');
    }

    public function langsAll()
    {
        return $this->hasMany(ExamLang::class, 'exam_id');
    }
}
