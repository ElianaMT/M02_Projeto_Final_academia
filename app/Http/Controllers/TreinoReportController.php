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
        //$exercises = $student->workouts->exercises;

        $pdf = Pdf::loadView('pdfs.treinoStudent',[
            'name' => $name,
            'workouts' => $workouts,
           // 'description' => $name
        ]);

        //return $student;

        return $pdf->stream(('treino.pdf'));
    }
}
