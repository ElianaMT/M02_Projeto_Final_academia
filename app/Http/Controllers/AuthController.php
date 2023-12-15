<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use HttpResponses;

   
    public function store(Request $request)
    {
        try {
            $data = $request->only('email', 'password');

            $request->validate([
                'email' => 'string|required',
                'password' => 'string|required'
            ]);

            //mensagem code 401

            $authenticated = Auth::attempt($data); //Verifica si usuario existe

            if (!$authenticated) {
                return $this->error('Não autorizado. Credenciais incorretas', Response::HTTP_UNAUTHORIZED);
            }

            //gera token acesso
            $request->user()->tokens()->delete();

            
            $token = $request->user()->createToken('simple');

            return $this->response('Autorizado', 200, [
                'token' => $token->plainTextToken, 
                'name' => $request->user()->name, // Adiciona o nome de usuario na resposta            
                'expiration_token' => "1 dia"
            ]);
            
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

}
