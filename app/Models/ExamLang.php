<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamLang extends Model
{
    use  SoftDeletes;
    protected $table = 'exams_langs';
}