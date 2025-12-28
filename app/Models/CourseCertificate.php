<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseCertificate extends Model
{
    use HasFactory;

    protected $table = 'course_certificates';

    protected $fillable = [
        'course_id',
        'user_id',
        'certificate_code',
        'file_url',
        'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
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
}
