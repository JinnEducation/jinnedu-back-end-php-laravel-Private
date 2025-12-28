<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseItem extends Model
{
    use HasFactory;

    protected $table = 'course_items';

    public const TYPE_LESSON_VIDEO      = 'lesson_video';
    public const TYPE_INTRO_ZOOM        = 'intro_zoom';
    public const TYPE_INTRO_RECORDING   = 'intro_recording';
    public const TYPE_WORKSHOP_ZOOM     = 'workshop_zoom';
    public const TYPE_WORKSHOP_RECORDING= 'workshop_recording';

    protected $fillable = [
        'course_id',
        'section_id',
        'type',
        'is_free_preview',
        'duration_seconds',
        'sort_order',
    ];

    protected $casts = [
        'is_free_preview' => 'boolean',
        'duration_seconds' => 'integer',
        'sort_order' => 'integer',
    ];

    /* =========================
     | Relations
     ========================= */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    public function langs()
    {
        return $this->hasMany(CourseItemLang::class, 'item_id');
    }

    public function media()
    {
        return $this->hasMany(CourseItemMedia::class, 'item_id');
    }

    public function liveSession()
    {
        return $this->hasOne(CourseLiveSession::class, 'item_id');
    }

    public function progresses()
    {
        return $this->hasMany(CourseItemProgress::class, 'item_id');
    }
}
