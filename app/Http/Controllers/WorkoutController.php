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
            
            //pega os daddos que foram enviados via query
            $filters = $request->query(); 

            //inicializa uma query
            $workouts = Workout::query()
            //traz tudas as colunas
            //->with('student')
            //traz solo certas colunas
            ->select(
                'workouts.student_id',
                'workouts.day as workouts',
                'workouts.repetitions as repetitions'
                )
            ->with(['student'=> function($query){
                $query->select('id','name');
                          
            }]);
            
            //verifica o filtro
            if($request->has('student_id')&&!empty($filters['student_id'])){ // mostra todos os estudantes por id asi no body eu nao coloque nada
                $workouts->where('student_id', 'ilike', '%'.$filters['student_id'].'%');  //filtra a tabela workouts por student_id        
            }

            if($request->has('day')&&!empty($filters['day'])){ 
                $workouts->where('day', 'ilike', '%'.$filters['day'].'%');      
            }
            
            //retorna resultado
            $columnOrder = $request->has('order')&&!empty($filters['order'])? $filters['order'] : 'student_id';
            $workouts = $workouts->orderBy($columnOrder)->get();

        

        return $workouts;            
    
           
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function store(Request $request)
    {
        try {
            // Obtenho usuario autenticado
            $user = Auth::user();
            $data= $request->all();

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
                    Rule::unique('workouts')->where(function ($query) use ($user,$data) {
                        return $query->where('user_id', $user->id)
                        ->where('student_id',$data['student_id']);
                    }),
                ],

                'observations' => 'nullable|string',
                'time' => 'required|string|max:10', 
            ]);

            $workout = Workout::create([...$request->all(),'user_id'=>$user->id]);
            
           
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
                'workout' => $workout
            ];

            return $responseData;
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
