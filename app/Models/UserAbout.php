<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAbout extends Model
{
    use HasFactory, SoftDeletes;
    
    //protected $table = 'users_abouts';


    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }
    
    public function level()
    {
        return $this->belongsTo(Level::class,'level_id');
    }
    
    public function language()
    {
        return $this->belongsTo(Language::class,'language_id');
    }
    
    public function subject()
    {
        return $this->belongsTo(Subject::class,'subject_id');
    }
    
    public function experience()
    {
        return $this->belongsTo(Experience::class,'experience_id');
    }
    
    public function situation()
    {
        return $this->belongsTo(Situation::class,'situation_id');
    }
    

   
}
