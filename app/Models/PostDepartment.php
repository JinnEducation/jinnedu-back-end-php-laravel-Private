<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostDepartment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    
    public function department()
    {
        return $this->belongsTo(Department::class,'depid');
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
