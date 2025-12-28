<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseItemLang extends Model
{
    use HasFactory;

    protected $table = 'course_item_langs';

    protected $fillable = [
        'item_id',
        'lang',
        'title',
        'description',
    ];

    /* =========================
     | Relations
     ========================= */
    public function item()
    {
        return $this->belongsTo(CourseItem::class, 'item_id');
    }
}
