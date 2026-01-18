<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $table = 'contactus';

    protected $fillable = [
        'f_name',
        'l_name',
        'email',
        'mobile',
        'message',
    ];
}
