<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseItemMedia extends Model
{
    use HasFactory;

    protected $table = 'course_item_media';

    public const SOURCE_UPLOAD = 'upload';
    public const SOURCE_URL    = 'url';

    protected $fillable = [
        'item_id',
        'source_type',
        'media_url',
    ];

    /* =========================
     | Relations
     ========================= */
    public function item()
    {
        return $this->belongsTo(CourseItem::class, 'item_id');
    }
}
