<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupClassAnswer extends Model
{
    protected $table = 'group_class_answers';
    protected $guarded = [];

    public function langs(){
        return $this->hasMany(GroupClassAnswerLang::class,'group_class_answer_id','id');
              
        $lang = $request->header('lang');

        return $lang ? 
                $this->hasMany(GroupClassAnswerLang::class,'group_class_answer_id','id')->where('language_id', $lang) 
                : $this->hasMany(GroupClassAnswerLang::class,'group_class_answer_id','id');
    }
}

