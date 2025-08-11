<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OurCourseLang extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function groupClass()
    {
        return $this->belongsTo(OurCourse::class, 'our_course_id');
    }

    public function tutor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
