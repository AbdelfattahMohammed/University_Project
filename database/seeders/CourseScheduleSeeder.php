<?php
// database/seeders/CourseScheduleSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Instructor;

class CourseScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // تأكد من وجود دكاترة ومعيدين وكورسات قبل تشغيل هذا الـ Seeder
        $professors = Instructor::where('position', 'Doctor')->get();
        $assistants = Instructor::where('position', 'Assistant')->get();
        $courses = Course::all();

        if ($professors->isEmpty() || $assistants->isEmpty() || $courses->isEmpty()) {
            $this->command->warn('Please run InstructorSeeder and CourseSeeder first.');
            return;
        }

        $days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
        $periods = [
            ['09:00', '11:00'],
            ['11:00', '13:00'],
            ['13:00', '15:00'],
            ['15:00', '17:00'],
        ];

        $profIndex = 0;
        $assIndex = 0;

        // مثال لربط بعض الكورسات بأيام محددة ودكاترة ومعيدين
        foreach ($courses as $course) {
            // نختار يوم عشوائي وفترة عشوائية للكورس
            $randomDay = $days[array_rand($days)];
            $randomPeriod = $periods[array_rand($periods)];

            // نختار دكتور ومعيد بالتناوب (Round-Robin)
            $professor = $professors[$profIndex % $professors->count()];
            $assistant = $assistants[$assIndex % $assistants->count()];

            // إذا كان الدكتور أو المعيد ملوش يوم عمل في اليوم ده (افتراضياً)
            // دي الجزئية اللي محتاجة تكون بياناتها دقيقة
            // حالياً، هنسجلهم عادي وبعدين نشوف في الـ Controller
            DB::table('course_schedules')->insert([
                'course_id' => $course->id,
                'professor_id' => $professor->id,
                'assistant_id' => $assistant->id,
                'day_of_week' => $randomDay,
                'start_time' => $randomPeriod[0],
                'end_time' => $randomPeriod[1],
                'room' => 'Room ' . chr(65 + rand(0, 5)) . rand(1, 5), // مثال لغرفة
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $profIndex++;
            $assIndex++;
        }
    }
}
