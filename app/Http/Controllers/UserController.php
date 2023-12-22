<?php

namespace App\Http\Controllers;

use App\Mail\SendWelcomeUser;
use App\Models\Exercise;
use App\Models\User;
use App\Models\Plan;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
         
          // Obten informações do plano
          $plan = Plan::find($user->plan_id);
                    
            Mail::to($user->email, $user->name)
                ->send(new SendWelcomeUser($user->name,$plan->description, $plan->limit));
          
         return $user;
        } catch (\Exception $exception) {
         return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
     }

     public function index(Request $request)
    {
        try {
            $user = Auth::user();

            $registered_students = Student::where('user_id', $request->user()->id)->count();
            $registered_exercises = Exercise::where('user_id', $request->user()->id)->count();

            $finalResponse =[
                'registered_students' => $registered_students,
                'registered_exercises' => $registered_exercises,
            ];

            return $finalResponse;
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }


    
 }
   

