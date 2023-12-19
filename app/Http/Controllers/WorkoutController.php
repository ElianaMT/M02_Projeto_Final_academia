<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Workout;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class WorkoutController extends Controller
{

    public function index(Request $request)
    {
        try {
            $user = Auth::user();
    
            $workouts = Workout::where('user_id', $user->id)
                ->orderBy('student_id')
                ->orderBy('day')
                ->get()
                ->groupBy('student_id','day');
    
            return $workouts;
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function store(Request $request)
    {
        try {
            // Obtén usuario autenticado
            $user = Auth::user();

            $request->validate([
                'student_id' => 'required|exists:students,id,user_id,' . $user->id,
                'exercise_id' => 'required|exists:exercises,id',
                'repetitions' => 'required|string',
                'weight' => 'required|decimal:2', // Hasta 2 decimales
                'break_time' => 'required|int',
                'day' => [
                    'required',
                    'string',
                    Rule::in(['SEGUNDA', 'TERCA', 'QUARTA', 'QUINTA', 'SEXTA', 'SÁBADO', 'DOMINGO']),
                    Rule::unique('workouts')->where(function ($query) use ($user) {
                        return $query->where('user_id', $user->id);
                    }),
                ],

                'observations' => 'nullable|string',
                'time' => 'required|string|max:10', 
            ]);

            $workout = new Workout($request->all());
            $workout->user_id = $user->id;
            $workout->save();
            

             // Encontra o estudante relacionado com o treino
             $student = Student::find($workout->student_id);

             // Verifica si o estudante existe
             if (!$student) {
                 return $this->error('Estudante não encontrado', Response::HTTP_NOT_FOUND);
             }
 
             // Verifica si usuario autenticado es igual al user_id del estudiante
             if ($user->id !== $student->user_id) {
                 return $this->error('Não tem permissão para visualizar este treino', Response::HTTP_FORBIDDEN);
             }
 
            
             $responseData = [
                'workout' => $workout,
                'student' => $student, //password oculto no estudante
            ];

            return $responseData;
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
