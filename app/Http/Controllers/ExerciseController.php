<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExerciseController extends Controller
{
    public function store(Request $request)
    {
                try {
                $request->validate([
                    'description' => 'required|string|max:255|unique:exercises'
                ]);
    
                $data = $request->all();
    
                $exercise = Exercise::create($data);
    
                return $exercise;
            } catch (Exception $exception) {
                return $this->error($exception->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

}
