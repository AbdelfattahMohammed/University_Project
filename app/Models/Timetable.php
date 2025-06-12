<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'period_number',
        'course_id',
        'professor_id',
        'assistant_id',
        'department_id',
        'year',
        'room_id',
    ];
     protected $table = 'timetables';

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function professor()
    {
        return $this->belongsTo(Instructor::class, 'professor_id');
    }

    public function assistant()
    {
        return $this->belongsTo(Instructor::class, 'assistant_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
