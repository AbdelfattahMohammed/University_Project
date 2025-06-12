<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;    // تأكد من استيراد موديل الطالب
use App\Models\Attendance; // تأكد من استيراد موديل الحضور
use Faker\Factory as Faker;
use Carbon\Carbon;         // لاستخدام التواريخ بسهولة

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $studentIds = Student::pluck('id')->toArray(); // جلب جميع IDs الطلاب الموجودين

        // تأكد أن هناك طلاب في قاعدة البيانات قبل محاولة إنشاء سجلات حضور لهم
        if (empty($studentIds)) {
            $this->command->info('No students found. Please seed the students table first.');
            return;
        }

        $attendanceRecords = [];
        $numberOfStudents = count($studentIds);
        $recordsPerStudent = 10; // عدد سجلات الحضور العشوائية لكل طالب
        $statusOptions = ['Present', 'Absent'];

        // نطاق التاريخ: آخر 5 أشهر من اليوم الحالي
        $startDate = Carbon::now()->subMonths(5)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $this->command->info("Seeding attendance records for {$numberOfStudents} students...");

        foreach ($studentIds as $studentId) {
            for ($i = 0; $i < $recordsPerStudent; $i++) {
                // اختيار تاريخ عشوائي ضمن النطاق المحدد
                $randomDate = $faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d');

                $attendanceRecords[] = [
                    'student_id' => $studentId,
                    'attendance_date' => $randomDate,
                    'status' => $faker->randomElement($statusOptions),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        // إدخال السجلات بكميات (chunks) لتجنب استهلاك الذاكرة بشكل كبير
        // كل 500 سجل في مرة واحدة (يمكنك تعديل هذا الرقم)
        $chunkSize = 500;
        foreach (array_chunk($attendanceRecords, $chunkSize) as $chunk) {
            Attendance::insert($chunk);
        }

        $this->command->info("Successfully seeded " . count($attendanceRecords) . " attendance records.");
    }
}
