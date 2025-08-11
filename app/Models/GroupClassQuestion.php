<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class GroupClassQuestion extends Model
{
    
    protected $table = 'group_class_question';


    protected $guarded = [];
    
    public function langs(){
        $lang = Request::header('lang');

        $language = Language::where('shortname', $lang)->first();

        return $language ? 
                $this->hasMany(GroupClassQuestionLang::class,'group_class_question_id','id')->where('language_id', $language->id) 
                : $this->hasMany(GroupClassQuestionLang::class,'group_class_question_id','id');
    }

    public function answers(){
        return $this->hasMany(GroupClassAnswer::class,'group_class_question_id','id');
    }
}
