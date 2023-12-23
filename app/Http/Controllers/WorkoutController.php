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

            //pega os dados que foram enviados via query
            $filters = $request->query();

            //inicializa uma query
            $workouts = Workout::query();

            // Verifica o filtro do student_id
            if ($request->has('student_id') && !empty($filters['student_id'])) { // mostra todos os estudantes por id asi no body eu nao coloque nada
                $workouts->where('student_id', $filters['student_id']);  //filtra a tabela workouts por student_id        
            }

            // Obter os resultados ordenados por día da semana
            $workouts = $workouts->orderBy('day')->get();

            // Verifica sim se encontraron workouts para el student_id 
            if ($request->has('student_id') && !empty($filters['student_id']) && $workouts->isEmpty()) {
                return $this->error('Id. não existe!.Não existen treinos para o student_id proporcionado.', Response::HTTP_BAD_REQUEST);
            }

            // Inicia o array de resultados
            $results = [];

            foreach ($workouts as $workout) {
                $day = strtoupper($workout->day); // Asegura que o dia esté em mayúsculas
                $exercise_id = $workout->exercise_id;
                $exercise_description = $workout->exercise->description;
                $repetitions = $workout->repetitions;
                $weight = $workout->weight;
                $break_time = $workout->break_time;
                $observations = $workout->observations;
                $time = $workout->time;

                // Response, workout por dia
                $results[$day][] = [
                    'exercise_id' => $exercise_id,
                    'exercise_description' => $exercise_description,
                    'repetitions' => $repetitions,
                    'weight' => $weight,
                    'break_time' => $break_time,
                    'observations' => $observations,
                    'time' => $time,
                ];
            }

            // Ordena os resultados por dias de la semana
            $orderedDays = ['SEGUNDA', 'TERCA', 'QUARTA', 'QUINTA', 'SEXTA', 'SÁBADO', 'DOMINGO'];
            $finalResults = [];

            foreach ($orderedDays as $day) {
                // Verifica sim hay dados para o día actual
                if (isset($results[$day])) {
                    // Agrega os dados ao novo array $finalResults
                    $finalResults[$day] = $results[$day];
                }
            }

            // Array final
            $finalResponse = [
                'student_id' => isset($workouts[0]) ? $workouts[0]->student->id : null,
                'student_name' => isset($workouts[0]) ? $workouts[0]->student->name : null,
                'workouts' => $finalResults,
            ];

            return $finalResponse;
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
