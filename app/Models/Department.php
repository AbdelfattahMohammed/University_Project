<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['department_name', 'head_of_department'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function instructors()
    {
        return $this->hasMany(Instructor::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_department', 'department_id', 'course_id')
            ->withPivot('year');
    }

    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'department_id', 'id');
    }
}
