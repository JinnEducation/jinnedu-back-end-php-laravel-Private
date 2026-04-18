<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    use HasFactory;

    protected $table = 'course_enrollments';

    protected $fillable = [
        'course_id',
        'user_id',
        'order_id',
        'enrolled_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
    ];

    /* =========================
     | Relations
     ========================= */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeForUserCourse(Builder $query, int $userId, int $courseId): Builder
    {
        return $query->where('course_enrollments.user_id', $userId)
            ->where('course_enrollments.course_id', $courseId);
    }

    public function scopeAccessible(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereHas('course', function (Builder $courseQuery) {
                $courseQuery->where('is_free', true)
                    ->orWhere('final_price', '<=', 0);
            })->orWhereHas('order', function (Builder $orderQuery) {
                $orderQuery->where('status', 1);
            });
        });
    }
}
