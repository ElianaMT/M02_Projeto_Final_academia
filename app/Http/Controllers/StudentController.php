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
            //Obtenho usuario autenticado
           //$user = Auth::user();

           $params=$request->query();

           $students = Student::query();

           if($request->has('name')){
            $students->where('name', $params['name']);
           }
           
            //Var_dump($request->query());

            // Obtenho usuario autenticado
           // $user = Auth::user();

             // Obtenho o valor da pesquisa desde a solicitude
           // $query = $request->input('query');

           //consulta na tabela students
           // $students = Student::query();

           // if ($query) {
              //  $students->where('name', 'ilike', '%' . $query . '%')
                //    ->orWhere('cpf', 'ilike', '%' . $query . '%')
                 //   ->orWhere('email', 'ilike', '%' . $query . '%');
            //}
            

           // Ordenado por o nome
      // $students->orderBy('name', 'asc');
       

       //Resultado de la consulta
       // $results = $students->get();

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
}
