<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ExerciseController extends Controller
{

    public function index(Request $request)
    {
        try {
            $userId = $request->input('user_id');

            $exercises = Exercise::query()
                ->where('user_id', $userId)
                ->orderBy('description')
                ->get();

            return $exercises;
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'description' => 'required|string|max:255|unique:exercises',                
            ]);

             // Obtenho usuario autenticado
             $user = Auth::user();

              // Cria novo exercicio y asigna user_id
            $exercise = new Exercise([
                'description' => $request->input('description'),
                'user_id' => $user->id,
            ]);

            $exercise->save();

            return $exercise;
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id){
        $exercise = Exercise::find($id);

        if(!$exercise) return $this->error('Dado não encontrado', Response::HTTP_NOT_FOUND);

        $exercise->delete();

        return $this->response('',Response::HTTP_NO_CONTENT);

    }
}
