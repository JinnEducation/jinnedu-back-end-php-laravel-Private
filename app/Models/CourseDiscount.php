<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseDiscount extends Model
{
    use HasFactory;

    protected $table = 'course_discounts';

    public const TYPE_PERCENT = 'percent';
    public const TYPE_FIXED   = 'fixed';

    protected $fillable = [
        'course_id',
        'type',
        'value',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /* =========================
     | Relations
     ========================= */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /* =========================
     | Helpers
     ========================= */
    public function isActiveNow(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->starts_at && now()->lt($this->starts_at)) {
            return false;
        }

        if ($this->ends_at && now()->gt($this->ends_at)) {
            return false;
        }

        return true;
    }
}
