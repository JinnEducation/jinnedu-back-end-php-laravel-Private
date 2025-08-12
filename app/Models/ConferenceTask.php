<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConferenceTask extends Model
{
    use HasFactory, SoftDeletes;
    
    //protected $table = 'users_abouts';


    protected $guarded = [];
    
    
    public function tutor()
    {
        return $this->belongsTo(User::class,'tutor_id');
    }
    
    public function conference()
    {
        return $this->belongsTo(Conference::class,'conference_id');
    }
    
    public function answers()
    {
        return $this->hasMany(ConferenceTaskAnswer::class,'task_id');
    }
   
}
