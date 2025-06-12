<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = ['full_name', 'department_id', 'position', 'work_days'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_instructor_assignments', 'instructor_id', 'course_id')
                    ->withPivot('role'); // لجلب الـ role (Professor/Assistant)
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceOfInstructor::class);
    }

   public function coursesAsProfessor()
    {
        return $this->belongsToMany(Course::class, 'course_instructor_assignments', 'instructor_id', 'course_id')
                    ->wherePivot('role', 'Professor');
    }

    // لو عايز تعرف ايه الكورسات اللي بيدرسها كمعيد
    public function coursesAsAssistant()
    {
        return $this->belongsToMany(Course::class, 'course_instructor_assignments', 'instructor_id', 'course_id')
                    ->wherePivot('role', 'Assistant');
    }

    public function teachingAssignments()
    {
        return $this->hasMany(CourseInstructorAssignment::class);
    }
}
