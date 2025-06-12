<?php
// database/migrations/YYYY_MM_DD_create_timetables_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->string('day'); // مثلا: Saturday, Sunday, etc.
            $table->integer('period_number'); // مثلا: 1 (9-11), 2 (11-1), 3 (1-3), وهكذا
            $table->unsignedBigInteger('course_id')->nullable();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null'); // لو اتمسحت المادة، الخانة تفضل فاضية
            $table->unsignedBigInteger('professor_id')->nullable(); // الدكتور
            $table->foreign('professor_id')->references('id')->on('instructors')->onDelete('set null');
            $table->unsignedBigInteger('assistant_id')->nullable(); // المعيد
            $table->foreign('assistant_id')->references('id')->on('instructors')->onDelete('set null');
            $table->string('department_id'); // لازم يكون من نوع string زي الـ departments table
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->integer('year'); // السنة (1, 2, 3, 4)

            $table->timestamps();

            // عشان ما يبقاش فيه أكتر من محاضرة في نفس اليوم والفترة لنفس القسم والسنة
            $table->unique(['day', 'period_number', 'department_id', 'year'], 'unique_timetable_slot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
