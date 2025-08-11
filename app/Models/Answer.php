<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;

class Answer extends Model
{
    use  SoftDeletes;
    
    protected $table = 'answers';


    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function langs(){

        $lang = Request::header('lang');

        $language = Language::where('shortname', $lang)->first();

        return $language ? 
                $this->hasMany(AnswerLang::class,'answer_id','id')->where('language_id', $language->id) 
                : $this->hasMany(AnswerLang::class,'answer_id','id');
    }
}
