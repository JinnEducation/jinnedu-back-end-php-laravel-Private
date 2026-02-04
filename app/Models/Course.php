<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'course_image',
        'course_duration_hours',
        'certificate_image',
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
        'course_duration_hours' => 'decimal:2',
        'published_at' => 'datetime',
    ];

    protected $append = [
        'final_price',
        'course_image_full',
        'certificate_image_full',
    ];

    /* =========================
     | Relations
     ========================= */
    public function category()
    {
        return $this->belongsTo(CourseCategory::class);
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
    // course_image
    public function getCourseImageFullAttribute() // $this->course_image
    {
        if (Str::startsWith($this->course_image, ['http', 'https'])) {
            return $this->course_image;
        }
        if ($this->course_image) {
            return asset('storage/' . $this->course_image);
        }

        return asset('front/assets/imgs/blogs/2.jpg');
    }
    // certificate_image
    public function getCertificateImageFullAttribute() // $this->certificate_image
    {
        if (Str::startsWith($this->certificate_image, ['http', 'https'])) {
            return $this->certificate_image;
        }
        if ($this->certificate_image) {
            return asset('storage/' . $this->certificate_image);
        }

        return asset('front/assets/imgs/cer1.jpg');
    }
}
