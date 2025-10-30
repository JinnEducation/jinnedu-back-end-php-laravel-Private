<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email_display',
        'country',
        'contact_number',
        'avatar_path',
        'terms_agreed',
    ];

    protected $casts = [
        'terms_agreed' => 'boolean',
    ];

    /**
     * العلاقة مع المستخدم الأساسي
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * إرجاع الاسم الكامل مباشرة
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }
}
