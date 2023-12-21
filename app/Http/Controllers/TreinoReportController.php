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
        $workoutsDay = $student->workouts;

        $workoutSegunda = $workoutsDay->filter(function ($workout) {
            return strtoupper($workout->day) === 'SEGUNDA';
        });
        
        // Iterar sobre os workouts da segunda
        foreach ($workoutSegunda as $workout) {
            $workout_id = $workout->id;
            $exercise_id = $workout->exercise_id;
            $repetitions = $workout->repetitions;
            $weight = $workout->weight;
            $break_time = $workout->break_time;
            $day = $workout->day;
            $observations = $workout->observations;
            $time = $workout->time;
        }

        $workoutTerca = $workoutsDay->filter(function ($workout) {
            return strtoupper($workout->day) === 'TERCA';
        });
        
        // Iterar sobre os workouts da terca
        foreach ($workoutTerca as $workout) {
            $workout_id = $workout->id;
            $exercise_id = $workout->exercise_id;
            $repetitions = $workout->repetitions;
            $weight = $workout->weight;
            $break_time = $workout->break_time;
            $day = $workout->day;
            $observations = $workout->observations;
            $time = $workout->time;
        }

        $workoutQuarta = $workoutsDay->filter(function ($workout) {
            return strtoupper($workout->day) === 'QUARTA';
        });
        
        // Iterar sobre os workouts da quarta
        foreach ($workoutQuarta as $workout) {
            $workout_id = $workout->id;
            $exercise_id = $workout->exercise_id;
            $repetitions = $workout->repetitions;
            $weight = $workout->weight;
            $break_time = $workout->break_time;
            $day = $workout->day;
            $observations = $workout->observations;
            $time = $workout->time;
        }

        $workoutQuinta = $workoutsDay->filter(function ($workout) {
            return strtoupper($workout->day) === 'QUINTA';
        });
        
        // Iterar sobre os workouts da quinta
        foreach ($workoutQuinta as $workout) {
            $workout_id = $workout->id;
            $exercise_id = $workout->exercise_id;
            $repetitions = $workout->repetitions;
            $weight = $workout->weight;
            $break_time = $workout->break_time;
            $day = $workout->day;
            $observations = $workout->observations;
            $time = $workout->time;
        }

        $workoutSexta = $workoutsDay->filter(function ($workout) {
            return strtoupper($workout->day) === 'SEXTA';
        });
        
        // Iterar sobre os workouts da Sexta
        foreach ($workoutSexta  as $workout) {
            $workout_id = $workout->id;
            $exercise_id = $workout->exercise_id;
            $repetitions = $workout->repetitions;
            $weight = $workout->weight;
            $break_time = $workout->break_time;
            $day = $workout->day;
            $observations = $workout->observations;
            $time = $workout->time;
        }

        $workoutSabado = $workoutsDay->filter(function ($workout) {
            return strtoupper($workout->day) === 'SÁBADO';
        });
        
        // Iterar sobre os workouts do dia sabado
        foreach ($workoutSabado as $workout) {
            $workout_id = $workout->id;
            $exercise_id = $workout->exercise_id;
            $repetitions = $workout->repetitions;
            $weight = $workout->weight;
            $break_time = $workout->break_time;
            $day = $workout->day;
            $observations = $workout->observations;
            $time = $workout->time;
        }

        $workoutDomingo = $workoutsDay->filter(function ($workout) {
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
            'workouts' => $workoutsDay,            
        ]);

        //return $student;

       return $pdf->stream(('treino.pdf'));
    }
}
