<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConferenceTaskAnswer extends Model
{
    use HasFactory, SoftDeletes;
    
    //protected $table = 'users_abouts';


    protected $guarded = [];
    
    
    public function student()
    {
        return $this->belongsTo(User::class,'student_id');
    }
    
    public function conference()
    {
        return $this->belongsTo(Conference::class,'conference_id');
    }
    
    public function task()
    {
        return $this->hasMany(ConferenceTask::class,'task_id');
    }
   
}
