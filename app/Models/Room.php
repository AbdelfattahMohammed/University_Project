<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['room_name', 'capacity'];

    // علاقة: القاعة الواحدة ممكن يكون ليها أكتر من موعد في الجدول (في أوقات مختلفة)
    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }
}
