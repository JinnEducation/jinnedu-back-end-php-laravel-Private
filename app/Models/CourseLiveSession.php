<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseLiveSession extends Model
{
    use HasFactory;

    protected $table = 'course_live_sessions';

    protected $fillable = [
        'item_id',
        'instructor_id',
        'start_at',
        'end_at',
        'zoom_meeting_id',
        'join_url_host',
        'join_url_attendee',
        'recording_item_id',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    /* =========================
     | Relations
     ========================= */
    public function item()
    {
        return $this->belongsTo(CourseItem::class, 'item_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function recordingItem()
    {
        return $this->belongsTo(CourseItem::class, 'recording_item_id');
    }
}
