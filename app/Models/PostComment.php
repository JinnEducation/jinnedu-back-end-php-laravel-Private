<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    
    public function language()
    {
        return $this->belongsTo(Language::class,'langid');
    }
    
    public function post()
    {
        return $this->belongsTo(Post::class,'postid');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

   
}
