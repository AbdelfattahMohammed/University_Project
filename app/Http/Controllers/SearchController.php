<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Department;
use App\Models\Course;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Room;
use App\Models\Timetable;
use App\Models\Financial;
use Illuminate\Database\Eloquent\Builder;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return view('search-results', [
                'query' => $query,
                'students' => collect(),
                'instructors' => collect(),
                'departments' => collect(),
                'courses' => collect(),
                'events' => collect(),
                'financials' => collect(),
                'attendance' => collect(),
                'rooms' => collect(),
                'timetables' => collect(),
            ]);
        }

        $students = Student::with('department')
            ->where('name', 'LIKE', "%$query%")
            ->orWhere('gender', 'LIKE', "%$query%")
            ->orWhere('address', 'LIKE', "%$query%")
            ->orWhere('phone', 'LIKE', "%$query%")
            ->orWhere('year', 'LIKE', "%$query%")
            ->orWhere('gpa', 'LIKE', "%$query%")
            ->orWhereHas('department', function (Builder $q) use ($query) {
                $q->where('department_name', 'LIKE', "%$query%");
            })
            ->get();

        // **هنا التعديل المهم:** استخدام 'department' (مفرد)
        $instructors = Instructor::with(['department', 'courses']) // <--- تم التعديل هنا
            ->where('full_name', 'LIKE', "%$query%")
            ->orWhere('position', 'LIKE', "%$query%")
            ->orWhere('work_days', 'LIKE', "%$query%")
            ->orWhereHas('department', function (Builder $q) use ($query) { // <--- تم التعديل هنا
                $q->where('department_name', 'LIKE', "%$query%");
            })
            ->orWhereHas('courses', function (Builder $q) use ($query) {
                $q->where('course_name', 'LIKE', "%$query%");
            })
            ->get();

        $departments = Department::where('department_name', 'LIKE', "%$query%")
            ->orWhere('head_of_department', 'LIKE', "%$query%")
            ->get();

        $courses = Course::with('departments')
            ->where('course_name', 'LIKE', "%$query%")
            ->orWhere('course_level', 'LIKE', "%$query%")
            ->orWhere('term', 'LIKE', "%$query%")
            ->orWhereHas('departments', function (Builder $q) use ($query) {
                $q->where('department_name', 'LIKE', "%$query%");
            })
            ->get();

        $events = Event::where('event_name', 'LIKE', "%$query%")
            ->orWhere('event_date', 'LIKE', "%$query%")
            ->orWhere('type', 'LIKE', "%$query%")
            ->orWhere('location', 'LIKE', "%$query%")
            ->get();

        $financials = Financial::where('department', 'LIKE', "%$query%")
            ->orWhere('year', 'LIKE', "%$query%")
            ->orWhere('revenue', 'LIKE', "%$query%")
            ->orWhere('expenses', 'LIKE', "%$query%")
            ->orWhere('profit', 'LIKE', "%$query%")
            ->get();

        $attendance = Attendance::with('student')
            ->where('status', 'LIKE', "%$query%")
            ->orWhere('attendance_date', 'LIKE', "%$query%")
            ->orWhereHas('student', function (Builder $q) use ($query) {
                $q->where('name', 'LIKE', "%$query%");
            })
            ->get();

        $rooms = Room::where('room_name', 'LIKE', "%$query%")
            ->orWhere('capacity', 'LIKE', "%$query%")
            ->get();

        $timetables = Timetable::with(['course', 'professor', 'assistant', 'department', 'room'])
            ->where('day', 'LIKE', "%$query%")
            ->orWhere('period_number', 'LIKE', "%$query%")
            ->orWhere('year', 'LIKE', "%$query%")
            ->orWhereHas('course', function (Builder $q) use ($query) {
                $q->where('course_name', 'LIKE', "%$query%");
            })
            ->orWhereHas('professor', function (Builder $q) use ($query) {
                $q->where('full_name', 'LIKE', "%$query%");
            })
            ->orWhereHas('assistant', function (Builder $q) use ($query) {
                $q->where('full_name', 'LIKE', "%$query%");
            })
            ->orWhereHas('department', function (Builder $q) use ($query) {
                 // هنا افترضنا أن department_id في Timetable هو unsignedBigInteger
                 // إذا كان string ويخزن اسم القسم، فستحتاج لتعديل هذا لـ $q->where('department_name', 'LIKE', "%$query%");
                $q->where('department_name', 'LIKE', "%$query%");
            })
            ->orWhereHas('room', function (Builder $q) use ($query) {
                $q->where('room_name', 'LIKE', "%$query%");
            })
            ->get();


        return view('search-results', compact(
            'query',
            'students',
            'instructors',
            'departments',
            'courses',
            'events',
            'financials',
            'attendance',
            'rooms',
            'timetables'
        ));
    }
}
