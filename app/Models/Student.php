<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'date_birth',
        'cpf',
        'cep',
        'street',
        'state',
        'neighborhood',
        'city',
        'number',
        'complement',
        'contact',
        'user_id'
    ];
}
