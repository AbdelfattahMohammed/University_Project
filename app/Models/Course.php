<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['course_name', 'course_level', 'term'];

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'course_department', 'course_id', 'department_id')
            ->withPivot('year');
    }

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class, 'course_instructor_assignments', 'course_id', 'instructor_id')
            ->withPivot('role');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }

    public function schedules()
    {
        return $this->hasMany(CourseSchedule::class);
    }
}
