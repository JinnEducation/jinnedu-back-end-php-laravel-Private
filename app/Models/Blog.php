<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;


     protected $table = 'blog';
    protected $fillable = [
        'cateq_blog_id','title','slug','description',
        'image','date','status',
        'published_at',
    ];

    public function category()
    {
        return $this->belongsTo(CateqBlog::class, 'cateq_blog_id');
    }

   
   public function scopePublished($q)
{
    return $q->where('status', 'published')
             ->whereDate('date', '<=', now()); 
}

}
