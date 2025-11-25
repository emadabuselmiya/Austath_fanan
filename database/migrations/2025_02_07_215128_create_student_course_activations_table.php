<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_course_activations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activation_code_id')->constrained('activation_codes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_course_activations');
    }
};
