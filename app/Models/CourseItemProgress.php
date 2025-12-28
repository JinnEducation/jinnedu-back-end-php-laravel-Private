<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseItemProgress extends Model
{
    use HasFactory;

    protected $table = 'course_item_progress';

    public const STATUS_NOT_STARTED = 'not_started';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED   = 'completed';

    protected $fillable = [
        'course_id',
        'item_id',
        'user_id',
        'status',
        'last_position_seconds',
        'completed_at',
    ];

    protected $casts = [
        'last_position_seconds' => 'integer',
        'completed_at' => 'datetime',
    ];

    /* =========================
     | Relations
     ========================= */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function item()
    {
        return $this->belongsTo(CourseItem::class, 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
