<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseSectionLang extends Model
{
    use HasFactory;

    protected $table = 'course_sections';

    protected $fillable = [
        'course_id',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /* =========================
     | Relations
     ========================= */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function langs()
    {
        return $this->hasMany(CourseSectionLang::class, 'section_id');
    }

    public function items()
    {
        return $this->hasMany(CourseItem::class, 'section_id')->orderBy('sort_order');
    }
}
