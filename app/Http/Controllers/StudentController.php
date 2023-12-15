<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{

    public function index(Request $request)
    {
        try {
            // Obtenho usuario autenticado
            $user = Auth::user();

            $students = Student::where('user_id', $user->id)
                ->orderBy('name')
                ->get();

            return $students;
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

            // Encontra o exercicio por id
            $student = Student::find($id);

            // Verifica si exercicio existe
            if (!$student) {
                return $this->error('Dado não encontrado', Response::HTTP_NOT_FOUND);
            }

            // Verifica si usuario autenticado e igual a user_id
            if ($user->id !== $student->user_id) {
                return $this->error('Nao tem permisos para eliminar este exercicio', Response::HTTP_FORBIDDEN);
            }

            $student->delete();

            return $this->response('Estudante deletado', Response::HTTP_NO_CONTENT);
        } catch (Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
