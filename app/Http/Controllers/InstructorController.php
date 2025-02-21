<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Instructor;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function index()
    {
        return view('instructor');
    }

    public function isIndex()
    {
        $department = Department::where('department_name', 'Information System')->first();

    // If the department exists
    if ($department) {
        // Get the head of department, or set to 'No Head' if not available
        $headOfDepartment = $department->head_of_department ?? 'No Head of Department';

        // Get all instructors for this department
        $instructors = Instructor::with('course')
            ->where('department_id', $department->department_id)
            ->get();

        // Pass the head of department and instructors to the view
        return view('is_instructor', compact('headOfDepartment', 'instructors'));
    }

    return redirect()->back()->with('error', 'Department not found');

    }

    public function csIndex()
    {
        $department = Department::where('department_name', 'Computer Science')->first();

    // Check if the department exists
    if ($department) {
        // Get the head of department, or set to 'No Head' if not available
        $headOfDepartment = $department->head_of_department ?? 'No Head of Department';

        // Get all instructors for this department
        $instructors = Instructor::with('course')
            ->where('department_id', $department->department_id)
            ->get();

        // Pass the head of department and instructors to the view
        return view('cs_instructor', compact('headOfDepartment', 'instructors'));
    }

    return redirect()->back()->with('error', 'Department not found');
    }

    public function aiIndex()
    {
        $department = Department::where('department_name', 'Artificial Intelligence')->first();

    // Check if the department exists
    if ($department) {
        // Get the head of department, or set to 'No Head' if not available
        $headOfDepartment = $department->head_of_department ?? 'No Head of Department';

        // Get all instructors for this department
        $instructors = Instructor::with('course')
            ->where('department_id', $department->department_id)
            ->get();

        // Pass the head of department and instructors to the view
        return view('ai_instructor', compact('headOfDepartment', 'instructors'));
    }

    return redirect()->back()->with('error', 'Department not found');
    }

    public function bioIndex()
    {
        $department = Department::where('department_name', 'Bioinformatics')->first();

    // Check if the department exists
    if ($department) {
        // Get the head of department, or set to 'No Head' if not available
        $headOfDepartment = $department->head_of_department ?? 'No Head of Department';

        // Get all instructors for this department
        $instructors = Instructor::with('course')
            ->where('department_id', $department->department_id)
            ->get();

        // Pass the head of department and instructors to the view
        return view('ai_instructor', compact('headOfDepartment', 'instructors'));
    }

    return redirect()->back()->with('error', 'Department not found');
    }

}
