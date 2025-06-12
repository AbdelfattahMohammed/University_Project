<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSchedule extends Model
{
    protected $fillable=['course_id','professor_id','assistant_id','day_of_week','start_time','end_time','room'];

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
}

