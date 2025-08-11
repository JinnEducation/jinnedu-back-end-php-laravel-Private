<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurrencyLatest extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'currency_latest';

    protected $guarded = [];
    
   
}
