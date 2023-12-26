<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TreinoReportController extends Controller
{
    public function showTreino(Request $request){
    
        $id = $request->input('id');

        $student = Student::with(['workouts' => function ($query) {
            $query->orderByRaw("CASE WHEN day = 'SEGUNDA' THEN 1
                                      WHEN day = 'TERCA' THEN 2
                                      WHEN day = 'QUARTA' THEN 3
                                      WHEN day = 'QUINTA' THEN 4
                                      WHEN day = 'SEXTA' THEN 5
                                      WHEN day = 'SÁBADO' THEN 6
                                      WHEN day = 'DOMINGO' THEN 7
                                 END");
        }])
        ->with('exercises')
        ->find($id);

        $name = $student->name;
        $workoutsDay = $student->workouts;

        $groupedWorkouts = [];

        // workouts por día da semana
        foreach ($workoutsDay as $workout) {
            $day = strtoupper($workout->day);
            $groupedWorkouts[$day][] = [
                'workout_id' => $workout->id,
                'exercise_id' => $workout->exercise_id,
                'exercise_description' => $workout->description,
                'repetitions' => $workout->repetitions,
                'weight' => $workout->weight,
                'break_time' => $workout->break_time,
                'day' => $day,
                'observations' => $workout->observations,
                'time' => $workout->time,
            ];
        }


        $pdf = Pdf::loadView('pdfs.treinoStudent',[
            'name' => $name,
            'workouts' => $workoutsDay,
        ]);

        //return $student;

       return $pdf->stream(('treino.pdf'));
    }
}
