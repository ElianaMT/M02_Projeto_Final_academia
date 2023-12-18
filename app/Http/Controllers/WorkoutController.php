<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class WorkoutController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Obtenho usuario autenticado
            $user = Auth::user();

            $request->validate([
                'repetitions' => 'string|date_format:Y-m-d|required',
                'weight' => 'required|numeric|between:0,9999.99', // deberia ter hasta 2 decimals
                'break_time' => 'int|required',
                'day' => 'string|in:SEGUNDA,TERCA,QUARTA,QUINTA,SEXTA,SÁBADO,DOMINGO',
                'observations' => 'string|nullable',
                'time' => 'string|max:10|requiered|unique:exercises',
                
            ]);


            // Cria novo treino y asigna al user_id
            $workout = new Workout([
                'user_id' => $user->id,
                'student_id' => $user->id,
                'exercise_id' => $user->id,

                'id' => $request->input('id'),
                'repetitions' => $request->input('repetitions'),
                'weight' => $request->input('weight'),
                'break_time' => $request->input('break_time'),
                'day' => $request->input('day'),
                'observations' => $request->input('observations'),
                'time' => $request->input('time'),  
            ]);

            $workout->save();

            return $workout;
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST); //status code 409
        }
    }
}
