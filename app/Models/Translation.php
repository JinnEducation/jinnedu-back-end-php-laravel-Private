<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Translation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function language()
    {
        return $this->belongsTo(Language::class, 'langid');
    }

    public function label()
    {
        return $this->belongsTo(Label::class, 'labelid');
    }


}