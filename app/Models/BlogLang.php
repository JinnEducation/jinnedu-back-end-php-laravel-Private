<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogLang extends Model
{
    use HasFactory;
    protected $table = 'blog_langs';
    protected $guarded = [];
    

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }

    public function language()
{
    return $this->belongsTo(Language::class, 'language_id');
}

}
