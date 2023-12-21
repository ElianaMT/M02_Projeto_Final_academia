<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TreinoReportController extends Controller
{
    public function showTreino(Request $request){
        $id = $request->input('id');

        $student = Student::
        with('workouts')
        ->with('exercises')
        ->find($id);

        $name = $student->name;
        $workouts = $student->workouts;
        $workoutDomingo = $workouts->filter(function ($workout) {
            return strtoupper($workout->day) === 'DOMINGO';
        });
        
        // Iterar sobre os workouts do dia domingo
        foreach ($workoutDomingo as $workout) {
            $workout_id = $workout->id;
            $exercise_id = $workout->exercise_id;
            $repetitions = $workout->repetitions;
            $weight = $workout->weight;
            $break_time = $workout->break_time;
            $day = $workout->day;
            $observations = $workout->observations;
            $time = $workout->time;
        }
       

        $pdf = Pdf::loadView('pdfs.treinoStudent',[
            'name' => $name,
            'workouts' => $workouts,
            'DOMINGO'=>$workoutDomingo,
            
        ]);

        return $student;

       //return $pdf->stream(('treino.pdf'));
    }
}
