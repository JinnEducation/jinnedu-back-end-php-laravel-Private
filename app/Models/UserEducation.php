<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserEducation extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'user_educations';


    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function degreeType()
    {
        return $this->belongsTo(DegreeType::class,'degree_type_id');
    }
    
    public function specialization()
    {
        return $this->belongsTo(Specialization::class,'specialization_id');
    }
   
}
