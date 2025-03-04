<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
    public function getWorkouts($id)
    {
        try {
            // Obtenho usuario autenticado
            $user = Auth::user();

            // Valida que user_id corresponde a quem criou os treinos
            $student = Student::where('user_id', $user->id)->findOrFail($id);
            $filteredWorkouts = $student->workouts;

            // Ordena os treinos por created_at
            $filteredWorkouts = $filteredWorkouts->sortBy('created_at');

            $results = [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'workouts' => [],
            ];

            foreach ($filteredWorkouts as $workout) {
                $day = strtoupper($workout->day);

                // Obtenho detalhes do exercicios
                $exerciseDescription = $workout->exercise->description;
                $repetitions = $workout->repetitions;
                $weight = $workout->weight;
                $breakTime = $workout->break_time;
                $observations = $workout->observations;
                $time = $workout->time;

                 // Agrupo os exercícios por día da semana
                if (!isset($results['workouts'][$day])) {
                    $results['workouts'][$day] = [];
                }

                $results['workouts'][$day][] = [
                    'exercise_description' => $exerciseDescription,
                    'repetitions' => $repetitions,
                    'weight' => $weight,
                    'break_time' => $breakTime,
                    'observations' => $observations,
                    'time' => $time,
                ];
            }

            return response()->json($results);
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    {
        try {
            // Obtenho usuario autenticado
            $user = Auth::user();
                        
            // Pesquiso o estudante por id
            $student = Student::find($id);

         // Verifica si estudante existe
         if (!$student) {
            return $this->error('Dado não encontrado', Response::HTTP_NOT_FOUND);
        }
           
            return ['id'=>$id,
            'name'=>$student->name,
            'email'=>$student->email,
            'date_birth'=>$student->date_birth,
            'cpf'=>$student->cpf,
            'address'=>[
            'cep'=>$student->cep,
            'street'=>$student->street,
            'province'=>$student->state,
            'neighboarhood'=>$student->neighboarhood,
            'city'=>$student->city,
            'complement'=>$student->complement,
            'number'=>$student->number,
        ]];
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function index(Request $request)
    {
        try {
            //Obtenho usuario autenticado
            $user = Auth::user();

            $params = $request->query();

            $students = Student::query();

            $students = Student::where('user_id', $user->id);

            if ($request->has('name') && !empty($params['name'])) {
                $students->where('name', 'ilike', '%' . $params['name'] . '%');//filtra en cualquier lugar de la string , en matyusculas y minisculas
            }

            if ($request->has('email') && !empty($params['email'])) {
                $students->where('email', 'ilike', '%' . $params['email'] . '%');
            }

            if ($request->has('cpf') && !empty($params['cpf'])) {
                $students->where('cpf', 'ilike', '%' . $params['cpf'] . '%');
            }
            // Ordenado por o nome
            $students->orderBy('name', 'asc');


            return $students->get();
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
                'name' => 'string|required|max:255',
                'email' => 'string|email|required|max:255|unique:students',
                'date_birth' => 'string|date_format:Y-m-d|required',
                'cpf' => 'string|required|size:14|regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/|unique:students',
                'contact' => 'string|required|max:20',
                'cep' => 'string|max:20',
                'street' => 'string|max:30',
                'state' => 'string|max:2',
                'neighborhood' => 'string|max:50',
                'city' => 'string|max:50',
                'complement' => 'string|max:50',
                'number' => 'string|max:30',
            ]);


            // Cria novo estudante y asigna al user_id
            $student = new Student([
                'user_id' => $user->id,

                'id' => $request->input('id'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'date_birth' => $request->input('date_birth'),
                'cpf' => $request->input('cpf'),
                'contact' => $request->input('contact'),
                'cep' => $request->input('cep'),
                'street' => $request->input('street'),
                'state' => $request->input('state'),
                'neighborhood' => $request->input('neighborhood'),
                'city' => $request->input('city'),
                'complement' => $request->input('complement'),
                'number' => $request->input('number'),


            ]);

            $student->save();

            return $student;
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST); //status code 409
        }
    }

    public function destroy($id)
    {
        try {
            // Obtenho usuario autenticado
            $user = Auth::user();

            // Encontra o estudante por id
            $student = Student::find($id);

            // Verifica si estudante existe
            if (!$student) {
                return $this->error('Dado não encontrado', Response::HTTP_NOT_FOUND);
            }

            // Verifica si usuario autenticado e igual a user_id
            if ($user->id !== $student->user_id) {
                return $this->error('Nao tem permisos para eliminar este estudante', Response::HTTP_FORBIDDEN);
            }

            $student->delete();

            return $this->response('Estudante deletado', Response::HTTP_NO_CONTENT);
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Obtenho usuario autenticado
            $user = Auth::user();

            //cria variavel $data
            $data = $request->all();

            // Valida os dados do request-corpo
            $request->validate([
                'name' => 'string|nullable|max:255',
                'email' => 'string|nullable|email|max:255|unique:students,email,' . $id,
                'date_birth' => 'string|nullable|date_format:Y-m-d',
                'cpf' => 'string|nullable|size:14|regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/|unique:students,cpf,' . $id,
                'contact' => 'string|required|max:20',
                'cep' => 'string|nullable',
                'street' => 'string|nullable',
                'state' => 'string|nullable',
                'neighborhood' => 'string|nullable',
                'city' => 'string|nullable',
                'complement' => 'string|nullable',
                'number' => 'string|nullable',
            ], [
                'email.unique' => 'O e-mail já está sendo usado',
                'cpf.unique' => 'O CPF já está sendo usado',
            ]);

            // Encontra o estudante por id
            $student = Student::find($id);

            // Verifica si estudante existe
            if (!$student) {
                return $this->error('Estudante não encontrado', Response::HTTP_NOT_FOUND);
            }

            // Verifica si el usuario autenticado es igual al user_id del estudiante
            if ($user->id !== $student->user_id) {
                return $this->error('Não tem permissão para atualizar este estudante', Response::HTTP_FORBIDDEN);
            }

            // Actualiza os dados do estudante
            $student->update($data);
            
            //hidden id
            $student->makeHidden('id');

            return $student;
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
