<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateqBlog extends Model
{
    use HasFactory;

     protected $table = 'categ_blog';

    protected $fillable = [
        'name','slug','sort_order','is_active','user_id'
    ];

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'categ_blog_id');
    }
}
