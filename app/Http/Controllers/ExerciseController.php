<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class ExerciseController extends Controller
{

    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            $exercises = Exercise::where('user_id', $user->id)
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
            // Obtenho usuario autenticado
            $user = Auth::user();

            $request->validate([
                'description' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('exercises')->where(function ($query) use ($user) {
                        return $query->where('user_id', $user->id);
                    }),
                ],
            ]);


            // Cria novo exercicio y asigna user_id
            $exercise = new Exercise([
                'description' => $request->input('description'),
                'user_id' => $user->id,
            ]);

            $exercise->save();

            return $exercise;
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_CONFLICT); //status code 409
        }
    }

    public function destroy($id)
    {
        try {
            // Obtenho usuario autenticado
            $user = Auth::user();

            // Encontra o exercicio por id
            $exercise = Exercise::find($id);

            // Verifica si exercicio existe
            if (!$exercise) {
                return $this->error('Dado não encontrado', Response::HTTP_NOT_FOUND);
            }

            // Verifica si usuario autenticado e igual a user_id
            if ($user->id !== $exercise->user_id) {
                return $this->error('Nao tem permisos para eliminar este exercicio', Response::HTTP_FORBIDDEN);
            }

            $exercise->delete();

            return $this->response('Exercício deletado', Response::HTTP_NO_CONTENT);
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
