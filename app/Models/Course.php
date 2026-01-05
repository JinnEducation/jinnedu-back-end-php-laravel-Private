<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    // Status
    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'category_id',
        'instructor_id',
        'promo_video_url',
        'promo_video_duration_seconds',
        'price',
        'is_free',
        'has_certificate',
        'status',
        'published_at',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'has_certificate' => 'boolean',
        'price' => 'decimal:2',
        'promo_video_duration_seconds' => 'integer',
        'published_at' => 'datetime',
    ];

    protected $append = [
        'final_price',
    ];

    /* =========================
     | Relations
     ========================= */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function discounts()
    {
        return $this->hasMany(CourseDiscount::class, 'course_id');
    }

    public function activeDiscount()
    {
        return $this->hasOne(CourseDiscount::class, 'course_id')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function langs()
    {
        return $this->hasMany(CourseLang::class, 'course_id');
    }

    public function sections()
    {
        return $this->hasMany(CourseSection::class, 'course_id')->orderBy('sort_order');
    }

    public function items()
    {
        return $this->hasMany(CourseItem::class, 'course_id')->orderBy('sort_order');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'course_id');
    }

    public function reviews()
    {
        return $this->hasMany(CourseReview::class, 'course_id');
    }

    public function certificates()
    {
        return $this->hasMany(CourseCertificate::class, 'course_id');
    }

    /* =========================
     | Accessors
     ========================= */

    // السعر النهائي حسب الخصم (إن وجد) + free
    public function getFinalPriceAttribute(): float
    {
        if ($this->is_free) {
            return 0.0;
        }

        $price = (float) $this->price;

        $discount = $this->activeDiscount()->first();

        if (! $discount) {
            return $price;
        }

        if ($discount->type === CourseDiscount::TYPE_PERCENT) {
            return max($price - ($price * $discount->value / 100), 0);
        }

        if ($discount->type === CourseDiscount::TYPE_FIXED) {
            return max($price - $discount->value, 0);
        }

        return $price;
    }

    public function getHasActiveDiscountAttribute(): bool
    {
        return $this->final_price < (float) $this->price;
    }
}
