<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Department;
use App\Models\Course;

class StudentSeeder extends Seeder
{
    public function run()
    {
       if (Department::count() === 0) {
            $this->call(DepartmentSeeder::class);
        }

        Student::factory()->count(2000)->create();
    }
}

