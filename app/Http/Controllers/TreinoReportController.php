<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class TreinoReportController extends Controller
{
    public function showTreino(Request $request){
        $id = $request->input('id');

        $student = Student::find($id);

        return $student;
    }
}
