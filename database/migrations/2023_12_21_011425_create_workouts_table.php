<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');            
            $table->unsignedBigInteger('exercise_id');  
            $table->unsignedBigInteger('user_id');  
            $table->integer('repetitions');
            $table->float('weight');
            $table->integer('break_time');
            $table->enum('day', ['SEGUNDA', 'TERCA', 'QUARTA', 'QUINTA','SEXTA','SÁBADO','DOMINGO']);
            $table->text('observations')->nullable(); 
            $table->integer('time');            
            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('exercise_id')->references('id')->on('exercises');
            $table->foreign('user_id')->references('id')->on('users');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workouts');
    }
};