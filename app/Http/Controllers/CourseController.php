<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return view('course');
    }
    
    public function semester1()
    {
        return view('index');
    }

    public function semester2()
    {
        return view('levels sem2');
    }
    
    public function semester1level1()
    {
        $courses = Course::where('course_level', 1)
                        ->where('term', 1)
                        ->with('instructors')
                        ->get()
                        ->groupBy('department_id');

        return view('level1', compact('courses'));
    }


    public function semester1level2()
    {
        $courses = Course::where('course_level', 2)
                        ->where('term', 1)
                        ->with('instructors')
                        ->get()
                        ->groupBy('department_id');

        return view('level2', compact('courses'));
    }
    
    public function semester1level3()
    {
        $courses = Course::where('course_level', 3)
                        ->where('term', 1)
                        ->with('instructors')
                        ->get()
                        ->groupBy('department_id');

        return view('level3', compact('courses'));
    }

    public function semester1level4()
    {
        $courses = Course::where('course_level', 4)
                        ->where('term', 1)
                        ->with('instructors')
                        ->get()
                        ->groupBy('department_id');

        return view('level4', compact('courses'));
    }

    public function semester2level1()
    {
        $courses = Course::where('course_level', 1)
                        ->where('term', 2)
                        ->with('instructors')
                        ->get()
                        ->groupBy('department_id');

        return view('semster2l1', compact('courses'));
    }
    
    public function semester2level2()
    {
        $courses = Course::where('course_level', 2)
        ->where('term', 2)
                        ->with('instructors')
                        ->get()
                        ->groupBy('department_id');
                        
                        return view('semster2l2', compact('courses'));
                    }
                    
                    public function semester2level3()
                    {
        $courses = Course::where('course_level', 3)
                        ->where('term', 2)
                        ->with('instructors')
                        ->get()
                        ->groupBy('department_id');

        return view('semster2l3', compact('courses'));
    }
    
    public function semester2level4()
    {
        $courses = Course::where('course_level', 4)
        ->where('term', 2)
        ->with('instructors')
        ->get()
        ->groupBy('department_id');
        
        return view('semster2l4', compact('courses'));
    }
    public function table()
    {
        return view('Tables.tables');
    }
}
