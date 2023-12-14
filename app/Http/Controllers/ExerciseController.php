<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\User;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function store(Request $request)
    {

        try {
            $data = $request->all();

            $request->validate([
                'description' => 'string|required|max:255'
            ]);

            $exercise = Exercise::create($data);

            $user = User::find($exercise->user_id);
            
            
            return $exercise;
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

}
