<?php

namespace App\Http\Controllers;

use App\Mail\SendWelcomeUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function store(Request $request) {
        try {
         $data = $request->all();
 
         $request->validate([
             'name' => 'string|max:255|required',
             'email' => 'email|required|unique:users',
             'date_birth' => 'string|date_format:Y-m-d|required',
             'cpf' => 'string|required|max:14|unique:users',             
             'password'=> 'string|required|min:8|max:32',
             'plan_id'=> 'integer|required'
         ]);
 
         $user = User::create($data);               

            Mail::to($user->email, $user->name)
                ->send(new SendWelcomeUser($user->name, 'Eliana Morillo'));
        
 
         return $user;
        } catch (\Exception $exception) {
         return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
     }
 
 }
   

