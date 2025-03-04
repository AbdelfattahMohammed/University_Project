<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $primaryKey = 'department_id';

    protected $fillable = ['department_name', 'head_of_department'];

    public function students()
    {
        return $this->hasMany(Student::class, 'department_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'department_id');
    }

    public function instructors()
    {
        return $this->hasMany(Instructor::class, 'department_id');
    }
}

