<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;

class Exam extends Model
{
    use  SoftDeletes;
    
    protected $table = 'exams';


    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function level(){
        return $this->belongsTo(Level::class,'level_id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function group_class(){
        return $this->belongsTo(GroupClass::class,'group_class_id');
    }

    public function langs(){
        $lang = Request::header('lang');

        $language = Language::where('shortname', $lang)->first();

        return $language ? 
                $this->hasMany(ExamLang::class,'exam_id', 'id')->where('language_id', $language->id) 
                : $this->hasMany(ExamLang::class,'exam_id', 'id');
    }

    public function answers(){
        return $this->hasMany(Answer::class,'exam_id','id');
    }
}
