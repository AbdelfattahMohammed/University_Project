<?php
// database/migrations/xxxx_xx_xx_create_course_schedules_table.php

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
        Schema::create('course_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('professor_id')->nullable(); // ID الدكتور
            $table->unsignedBigInteger('assistant_id')->nullable(); // ID المعيد
            $table->enum('day_of_week', ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']); // اليوم اللي الكورس بيتاخد فيه
            $table->string('start_time', 5); // مثلاً '09:00'
            $table->string('end_time', 5);   // مثلاً '11:00'
            $table->string('room', 50)->nullable(); // الغرفة (اختياري)
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('professor_id')->references('id')->on('instructors')->onDelete('set null');
            $table->foreign('assistant_id')->references('id')->on('instructors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_schedules');
    }
};
