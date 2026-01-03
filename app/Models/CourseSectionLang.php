<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseSectionLang extends Model
{
    use HasFactory;

    protected $table = 'course_section_langs';

    protected $fillable = [
        'section_id',
        'lang',
        'title',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /* =========================
     | Relations
     ========================= */
    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }
}
