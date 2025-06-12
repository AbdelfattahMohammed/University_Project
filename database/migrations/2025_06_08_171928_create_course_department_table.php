<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_department', function (Blueprint $table) {

            $table->foreignId('course_id')
                ->constrained('courses')
                ->onDelete('cascade');
            $table->foreignId('department_id')
                ->constrained('departments')
                ->onDelete('cascade');
            $table->integer('year');

            $table->primary(['course_id', 'department_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_department');
    }
};
