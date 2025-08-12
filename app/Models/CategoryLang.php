<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryLang extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function language()
    {
        return $this->belongsTo(Language::class, 'langid');
    }

    public function myParent()
    {
        return $this->belongsTo(Category::class, 'catid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
