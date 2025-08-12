<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConferenceComplaint extends Model
{
    use HasFactory, SoftDeletes;
    
    //protected $table = 'users_abouts';


    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function tutor()
    {
        return $this->belongsTo(User::class,'tutor_id');
    }
    
    public function student()
    {
        return $this->belongsTo(User::class,'student_id');
    }
    
    public function conference()
    {
        return $this->belongsTo(Conference::class,'conference_id');
    }
    
    public function replies()
    {
        return $this->hasMany(ConferenceComplaint::class,'reply_id');
    }
   
}
