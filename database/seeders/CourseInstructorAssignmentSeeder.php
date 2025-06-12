<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Instructor;

class CourseInstructorAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();
        $professors = Instructor::where('position', 'Doctor')->get(); // Use 'Professor' if that's the position for Professors
        $assistants = Instructor::where('position', 'Assistant')->get();

        // If no professors or assistants exist, stop to prevent errors
        if ($professors->isEmpty()) {
            $this->command->error("No instructors with position 'Professor' found. Please seed instructors first.");
            return;
        }
        if ($assistants->isEmpty()) {
            $this->command->error("No instructors with position 'Assistant' found. Please seed instructors first.");
            return;
        }

        // Convert collections to arrays for easier indexing with Round-Robin
        $professorsArray = $professors->toArray();
        $assistantsArray = $assistants->toArray();

        $numProfessors = count($professorsArray);
        $numAssistants = count($assistantsArray);

        // Make sure there's at least one professor and one assistant
        if ($numProfessors === 0 || $numAssistants === 0) {
            $this->command->warn("Not enough professors or assistants to assign to all courses.");
            return;
        }

        $profIndex = 0;
        $assIndex = 0;

        foreach ($courses as $course) {
            try {
                // Assign Professor (Round-Robin)
                $currentProfessor = $professorsArray[$profIndex % $numProfessors];
                DB::table('course_instructor_assignments')->insert([
                    'course_id' => $course->id,
                    'instructor_id' => $currentProfessor['id'], // Access ID from array
                    'role' => 'Professor',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $profIndex++; // Increment index for next professor

                // Assign Assistant (Round-Robin)
                $currentAssistant = $assistantsArray[$assIndex % $numAssistants];
                DB::table('course_instructor_assignments')->insert([
                    'course_id' => $course->id,
                    'instructor_id' => $currentAssistant['id'], // Access ID from array
                    'role' => 'Assistant',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $assIndex++; // Increment index for next assistant

            } catch (\Illuminate\Database\QueryException $e) {
                // This catch block will now primarily catch the `uq_course_role` constraint (one professor/assistant per course)
                // if you try to run the seeder multiple times without clearing previous assignments.
                // Or other database errors.
                $this->command->error("Assignment failed for Course {$course->id}: " . $e->getMessage());
            }
        }
    }
}
