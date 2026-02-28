<?php

namespace App\Models;

use App\Models\ConferenceRecording;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conference extends Model
{
    use HasFactory, SoftDeletes;
    
    //protected $table = 'users_abouts';


    protected $guarded = [];
    
    public function tutor()
    {
        return $this->belongsTo(Tutor::class,'tutor_id');
    }
    
    public function student()
    {
        return $this->belongsTo(Student::class,'student_id');
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
    
    public function links()
    {
        return $this->hasMany(ConferenceLink::class,'conference_id');
    }
    
    public function files()
    {
        return $this->hasMany(ConferenceFile::class,'conference_id');
    }
    
    public function notes()
    {
        return $this->hasMany(ConferenceNote::class,'conference_id');
    }
    
    public function complaints()
    {
        return $this->hasMany(ConferenceComplaint::class,'conference_id');
    }
    
    public function reviews()
    {
        return $this->hasMany(ConferenceReview::class,'conference_id');
    }
    
    public function tasks()
    {
        return $this->hasMany(ConferenceTask::class,'conference_id');
    }

    public function attendances()
    {
        return $this->hasMany(ConferenceAttendance::class, 'conference_id');
    }

    public function recordings()
{
    return $this->hasMany(ConferenceRecording::class, 'conference_id');
}
   
}
