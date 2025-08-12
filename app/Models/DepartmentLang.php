<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepartmentLang extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function myParent()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
