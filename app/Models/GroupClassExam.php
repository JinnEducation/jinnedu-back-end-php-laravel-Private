<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupClassExam extends Model
{
    
    protected $table = 'group_class_exam';


    protected $guarded = [];
    
    public function exam(){
        return $this->hasMany(GroupClassQuestion::class,'group_class_exam_id','id');
    }
}
