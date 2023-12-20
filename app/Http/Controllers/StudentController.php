<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{

    public function show($id)
    {
        try {
            // Obtenho usuario autenticado
            $user = Auth::user();
                        
            // Pesquiso o estudante por id
            $student = Student::find($id);

         
           
            return $student;
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
                'email' => 'string|required|max:255|unique:students',
                'date_birth' => 'string|date_format:Y-m-d|required',
                'cpf' => 'string|required|max:255|unique:students',
                'contact' => 'string|required|max:20|unique:students',
                'cep' => 'string|required|max:20',
                'street' => 'string|required|max:30',
                'state' => 'string|required|max:2',
                'neighborhood' => 'string|required|max:50',
                'city' => 'string|required|max:50',
                'complement' => 'string|max:50',
                'number' => 'string|required|max:30',
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
                'email' => 'string|nullable|max:255|unique:students,email,' . $id,
                'date_birth' => 'string|nullable|date_format:Y-m-d',
                'cpf' => 'string|nullable|max:255|unique:students,cpf,' . $id,
                'contact' => 'string|required|max:20|unique:students,contact,' . $id,
                'cep' => 'string|nullable',
                'street' => 'string|nullable',
                'state' => 'string|nullable',
                'neighborhood' => 'string|nullable',
                'city' => 'string|nullable',
                'complement' => 'string|nullable',
                'number' => 'string|nullable',
            ], [
                'email.unique' => 'O e-mail já está sendo usado',
                'cpf.unique' => 'O CPF já está sendo usado.',
                'contact.unique' => 'O contato já está sendo usado',
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

            return $student;
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
