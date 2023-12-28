<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Workout;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class WorkoutController extends Controller
{


    public function index(Request $request)
    {
        try {
            $user = Auth::user();
    
            // Obter o student_id pasapo por URL
            $studentId = $request->input('student_id');
    
            $workouts = Workout::where('user_id', $user->id)
                ->orderBy('created_at')
                ->get();
    
            // Filtra por student_id 
            $filteredWorkouts = $workouts->filter(function ($workout) use ($studentId) {
                return $studentId ? $workout->student_id == $studentId : true;
            });
    
            return $filteredWorkouts;
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
    


    public function store(Request $request)
    {
        try {
            // Obtengo usuario autenticado
            $user = Auth::user();
            $data = $request->all();

            $request->validate([
                'student_id' => 'required|exists:students,id,user_id,' . $user->id,
                'exercise_id' => 'required|exists:exercises,id',
                'repetitions' => 'required|int',
                'weight' => 'required|decimal:2', // Hasta 2 decimales
                'break_time' => 'required|int',
                'day' => 'required|in:SEGUNDA,TERCA,QUARTA,QUINTA,SEXTA,SÁBADO,DOMINGO',
                'observations' => 'nullable|string',
                'time' => 'required|string|max:10',
            ]);

            // Valida que o exercicio nao se repita para o mismo dia
            $existingWorkout = Workout::where([
                'student_id' => $request->input('student_id'),
                'exercise_id' => $request->input('exercise_id'),
                'day' => $request->input('day'),
            ])->first();

            if ($existingWorkout) {
                return $this->error('Este exercício já foi cadastrado para este dia', Response::HTTP_CONFLICT);
            }

            $workout = Workout::create([...$request->all(), 'user_id' => $user->id]);

            // Encontra o estudante relacionado com o treino
            $student = Student::find($workout->student_id);

            // Verifica si o estudante existe
            if (!$student) {
                return $this->error('Estudante não encontrado', Response::HTTP_NOT_FOUND);
            }

            // Verifica si el usuario autenticado es igual al user_id del estudiante
            if ($user->id !== $student->user_id) {
                return $this->error('Não tem permissão para visualizar este treino', Response::HTTP_FORBIDDEN);
            }

            $responseData = [
                'workout' => $workout
            ];

            return $responseData;
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
