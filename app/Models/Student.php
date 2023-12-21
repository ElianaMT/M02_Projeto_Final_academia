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
    
    public function workout(){
        return $this->hasOne(Workout::class,'id', 'workout_id');
    }
    public function exercise(){
        return $this->hasOne(Exercise::class,'id', 'exercise_id');
    }
 
}
