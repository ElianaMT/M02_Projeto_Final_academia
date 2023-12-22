<?php

namespace App\Http\Middleware;

use App\Models\Plan;
use App\Models\Student;
use App\Traits\HttpResponses;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateLimitStudentsToUser
{
    use HttpResponses;

    public function handle(Request $request, Closure $next): Response
    {

        $user = Auth::user();
        $registered_students = Student::where('user_id', $request->user()->id)->count();

        $current_user_plan = Plan::find($user->plan_id);
        //Quantidade de alunos permitidos por o plano
        $plan_limit = $current_user_plan->limit;

        if ($registered_students >= $plan_limit) return $this->error('O usuário atingiu o limite', 403);


        return $next($request);
    }
}
