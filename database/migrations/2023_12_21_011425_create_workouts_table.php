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
            $table->unsignedBigInteger('student_id')->nullable();            
            $table->unsignedBigInteger('exercise_id')->nullable();  
            $table->unsignedBigInteger('user_id')->nullable();  
            $table->date('repetitions')->nullable();
            $table->float('weight')->nullable();
            $table->integer('break_time')->nullable();
            $table->enum('day', ['SEGUNDA', 'TERCA', 'QUARTA', 'QUINTA','SEXTA','SÁBADO','DOMINGO']);
            $table->text('observations')->nullable(); 
            $table->string('time',10)->nullable();            
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