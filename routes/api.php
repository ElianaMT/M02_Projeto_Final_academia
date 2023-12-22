<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TreinoReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkoutController;
use App\Http\Middleware\ValidateLimitStudentsToUser;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // rotas privadas
Route::post('exercises', [ExerciseController::class, 'store']);
Route::get('exercises', [ExerciseController::class, 'index']);
Route::delete('exercises/{id}', [ExerciseController::class, 'destroy']);

Route::get('students/treino', [TreinoReportController::class, 'showTreino']);
Route::get('students/{id}', [StudentController::class, 'show']);
Route::post('students', [StudentController::class, 'store'])->middleware([ValidateLimitStudentsToUser::class]);
Route::get('students', [StudentController::class, 'index']);
Route::delete('students/{id}', [StudentController::class, 'destroy']);
Route:: put('students/{id}', [StudentController::class,'update']);

Route::post('workouts', [WorkoutController::class, 'store']);
Route::get('workouts', [WorkoutController::class, 'index']);

Route::get('dashboard', [UserController::class, 'index']);

});

// rota pública
Route::post('users', [UserController::class, 'store']);
Route::post('login', [AuthController::class, 'store']);


