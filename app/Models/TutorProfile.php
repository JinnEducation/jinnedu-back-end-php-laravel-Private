<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dob',
        'tutor_country',
        'native_language',
        'teaching_subject',
        'teaching_experience',
        'situation',
        'headline',
        'interests',
        'motivation',
        'specializations',
        'experience_bio',
        'methodology',
        'availability_json',
        'hourly_rate',
        'certifications_json',
        'video_path',
        'video_terms_agreed',
    ];

    protected $casts = [
        'dob' => 'date',
        'availability_json' => 'array',
        'certifications_json' => 'array',
        'video_terms_agreed' => 'boolean',
    ];

    /**
     * العلاقة مع المستخدم الأساسي
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * فورماتر بسيط لعرض السعر بالساعة
     */
    public function getHourlyRateLabelAttribute(): ?string
    {
        return $this->hourly_rate ? "{$this->hourly_rate} / hour" : null;
    }

    /**
     * استخراج الشهادات كمصفوفة مفهومة
     */
    public function getCertificationsAttribute(): array
    {
        return $this->certifications_json ?? [];
    }

    /**
     * استخراج جدول التوفر بشكل مهيأ
     */
    public function getAvailabilityAttribute(): array
    {
        return $this->availability_json ?? [];
    }
}
