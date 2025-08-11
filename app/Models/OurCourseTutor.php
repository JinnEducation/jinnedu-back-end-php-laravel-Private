<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OurCourseTutor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];


    public function OurCourse()
    {
        return $this->belongsTo(OurCourse::class, 'our_course_id');
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class, 'tutor_id');
    }

}
