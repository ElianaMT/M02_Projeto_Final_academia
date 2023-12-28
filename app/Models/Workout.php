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
        'exercise_id',
        'student_id'       
                
    ];
    
    protected $hidden = ['created_at','updated_at'];

    //Relações para listar treinos

    public function student(){
        return $this->hasOne(Student::class,'id', 'student_id');
    }
    public function exercise(){
        return $this->hasOne(Exercise::class,'id', 'exercise_id');
    }

     
}
