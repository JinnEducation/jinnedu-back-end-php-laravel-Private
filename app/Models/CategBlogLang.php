<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategBlogLang extends Model
{
    use HasFactory;
    protected $table = 'categ_blog_langs';
    protected $guarded = [];
    

    public function categblog()
    {
        return $this->belongsTo(CateqBlog::class, 'categ_blog_id');
    }

    public function language()
{
    return $this->belongsTo(Language::class, 'language_id');
}
}
