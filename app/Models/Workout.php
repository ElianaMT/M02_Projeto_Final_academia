<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{

   

    use HasFactory;
    protected $fillable = [
        'repetitions',
        'weight',
        'break_time',
        'day',
        'observations',
        'time',
        'user_id',   
        'student_id',   
        'exercise_id' 
                
    ];
    
    protected $hidden = ['created_at','updated_at'];
}
