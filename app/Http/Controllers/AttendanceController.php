<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('attendence');
    }

     /**
     * Get attendance records for students of a specific year (level).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $year
     * @return \Illuminate\Http\JsonResponse
     */
    // public function getAttendanceByYear(Request $request, $year)
    // {
    //     $query = Student::leftJoin('attendance', function ($join) {
    //             $join->on('students.id', '=', 'attendance.student_id');
    //         })
    //         ->select('students.name', 'attendance.attendance_date', 'attendance.status', 'students.year')
    //         ->where('students.year', $year); // فلترة أساسية حسب السنة

    //     // Filter by date if provided
    //     if ($request->has('date') && !empty($request->date)) {
    //         $query->whereDate('attendance.attendance_date', $request->date);
    //     }

    //     $attendanceRecords = $query->get();

    //     return response()->json($attendanceRecords);
    // }

    // الدالة getAttendanceRate ستبقى كما هي
    public function getAttendanceRate()
    {
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $attendanceRates = [];

        $totalStudents = Student::count(); // Get total students

        for ($i = 0; $i < 5; $i++) { // Last 5 months
            $month = Carbon::now()->subMonths($i)->month;
            $year = Carbon::now()->subMonths($i)->year;

            $present = Attendance::whereMonth('attendance_date', $month)
                ->whereYear('attendance_date', $year)
                ->where('status', 'Present')
                ->count();

            // Calculate attendance rate considering total students
            $rate = $totalStudents > 0 ? round(($present / $totalStudents) * 100, 2) : 0;

            $attendanceRates[] = [
                'month' => $months[$month - 1],
                'rate' => $rate
            ];
        }

        $overallRate = count($attendanceRates) > 0 ? round(array_sum(array_column($attendanceRates, 'rate')) / count($attendanceRates), 2) : 0;
        $change = count($attendanceRates) > 1 ? round($attendanceRates[0]['rate'] - $attendanceRates[1]['rate'], 2) : 0;

        return response()->json([
            'overallRate' => $overallRate,
            'change' => $change,
            'monthlyRates' => array_reverse($attendanceRates), // Reverse to start from oldest month
        ]);
    }

    public function getAttendanceByYear(Request $request, $year)
    {
        // الحصول على التاريخ المحدد من الـ request، أو تاريخ اليوم إذا لم يتم تحديده
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));
        // الحصول على اسم الطالب من الـ request
        $studentName = $request->input('student_name');

        // نبدأ ببناء الاستعلام
        // نربط جدول الطلاب بجدول الحضور
        $query = Student::select(
            'students.name',
            'attendance.attendance_date',
            'attendance.status'
        )
        // نستخدم leftJoin لضمان عرض جميع الطلاب حتى لو لم يكن لديهم سجل حضور لليوم المحدد
        ->leftJoin('attendance', function ($join) use ($selectedDate) {
            $join->on('students.id', '=', 'attendance.student_id')
                 ->whereDate('attendance.attendance_date', $selectedDate); // فلترة بسجل الحضور لليوم المحدد فقط
        })
        ->where('students.year', $year); // فلترة بالعام الدراسي

        // إذا تم توفير اسم الطالب، أضف فلتر البحث بالاسم
        if ($studentName) {
            $query->where('students.name', 'like', '%' . $studentName . '%');
        }

        $attendanceRecords = $query->get();

        return response()->json($attendanceRecords);
    }

}
