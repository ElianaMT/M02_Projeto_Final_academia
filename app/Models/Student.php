<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'date_birth',
        'cpf',
        'contact',
        'cep',
        'street',
        'state',
        'neighborhood',
        'city',
        'complement',
        'number',
        'user_id'         
                
    ];
    
    protected $hidden = ['created_at','updated_at','user_id','deleted_at'];
    

    //Relações para pdf
    public function workouts()
    {
        return $this->hasMany(Workout::class, 'student_id');
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'workouts', 'student_id', 'exercise_id');
    }
 
}
