<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Post
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events';


}
