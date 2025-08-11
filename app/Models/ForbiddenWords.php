<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForbiddenWords extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'forbidden_words';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['word','status'];
}
