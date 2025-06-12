<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class CourseDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // For security and to avoid duplicates, clear the pivot table first
        // Make sure this line is uncommented unless you have a specific reason!
        DB::table('course_department')->truncate();

        // Define a mapping from the string department IDs to their actual numeric IDs
        // Make sure Department Seeder runs before this seeder to populate departments.
        $departmentMapping = [
            'CS001' => Department::where('department_name', 'CS')->first()?->id,
            'IS001' => Department::where('department_name', 'IS')->first()?->id,
            'AI001' => Department::where('department_name', 'AI')->first()?->id,
            'BIO001' => Department::where('department_name', 'BIO')->first()?->id,
            // Assuming AI002, AI003, etc., are typos and map to AI001's department ID
            'AI002' => Department::where('department_name', 'AI')->first()?->id,
            'AI003' => Department::where('department_name', 'AI')->first()?->id,
            'AI004' => Department::where('department_name', 'AI')->first()?->id,
            'AI005' => Department::where('department_name', 'AI')->first()?->id,
            'AI006' => Department::where('department_name', 'AI')->first()?->id,
        ];

        // Define year descriptions (optional, for display purposes in console/frontend)
        $yearDescriptions = [
            1 => 'First Year',
            2 => 'Second Year',
            3 => 'Third Year',
            4 => 'Fourth Year',
        ];

        $courseDepartmentData = [
            // Year 1 Courses
            ['course_name' => 'Human Rights', 'course_level' => 1, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
            ['course_name' => 'Scientific & Technical Report Writing', 'course_level' => 1, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
            ['course_name' => 'Mathematics-1', 'course_level' => 1, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
            ['course_name' => 'Discrete Mathematics', 'course_level' => 1, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
            ['course_name' => 'Computer Introduction', 'course_level' => 1, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
            ['course_name' => 'Semiconductors', 'course_level' => 1, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
            ['course_name' => 'Logic Design -1', 'course_level' => 1, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
            ['course_name' => 'Introduction to IS', 'course_level' => 1, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
            ['course_name' => 'Fundamentals of Economics', 'course_level' => 1, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
            ['course_name' => 'Professional Ethics', 'course_level' => 1, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
            ['course_name' => 'Mathematics-2', 'course_level' => 1, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
            ['course_name' => 'Fundamentals of Programming', 'course_level' => 1, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],

            // Year 2 Courses
            ['course_name' => 'Computer Programming – 1', 'course_level' => 2, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
            ['course_name' => 'Computer Architecture & organization', 'course_level' => 2, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
            ['course_name' => 'Systems Analysis & Design -1', 'course_level' => 2, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
            ['course_name' => 'Computer Networks', 'course_level' => 2, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
            ['course_name' => 'Statistics & Probabilities', 'course_level' => 2, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
            ['course_name' => 'Microprocessors and Assembly language', 'course_level' => 2, 'department_ids_string' => 'CS001'],
            ['course_name' => 'IS Strategy, Management & Acquisition', 'course_level' => 2, 'department_ids_string' => 'AI001'],
            ['course_name' => 'IS Strategy, Management & Acquisition', 'course_level' => 2, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Mathematics-3', 'course_level' => 2, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Multimedia', 'course_level' => 2, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
            ['course_name' => 'Web Design and Development', 'course_level' => 2, 'department_ids_string' => 'CS001-IS001-BIO001-AI002'],
            ['course_name' => 'Computer Programming-2', 'course_level' => 2, 'department_ids_string' => 'CS001-IS001-BIO001-AI003'],
            ['course_name' => 'Data Structure', 'course_level' => 2, 'department_ids_string' => 'CS001-IS001-BIO001-AI004'],
            ['course_name' => 'Operating Systems-1', 'course_level' => 2, 'department_ids_string' => 'CS001-IS001-BIO001-AI005'],
            ['course_name' => 'Introduction to Operation Research & Decision Support', 'course_level' => 2, 'department_ids_string' => 'CS001-IS001-BIO001-AI006'],

            // Year 3 Courses (CS)
            ['course_name' => 'Software Engineering-1', 'course_level' => 3, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Database Systems-1', 'course_level' => 3, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Analysis and Design of Algorithms', 'course_level' => 3, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Computer Programming-3', 'course_level' => 3, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Introduction in Artificial Intelligence', 'course_level' => 3, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Artificial intelligence', 'course_level' => 3, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Machine learning', 'course_level' => 3, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Software Engineering-2', 'course_level' => 3, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Operating Systems-2', 'course_level' => 3, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Internet Computing', 'course_level' => 3, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Advanced Computer Programming', 'course_level' => 3, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Modeling & Simulation', 'course_level' => 3, 'department_ids_string' => 'CS001'],

            // Year 3 Courses (IS)
            ['course_name' => 'Software Engineering-1', 'course_level' => 3, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Analysis and Design of Algorithms', 'course_level' => 3, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Database Systems-1', 'course_level' => 3, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Modeling & Simulation', 'course_level' => 3, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Systems Analysis and Design 2', 'course_level' => 3, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Information Security', 'course_level' => 3, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Database Systems-2', 'course_level' => 3, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Information Storage & Retrieval', 'course_level' => 3, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Enterprise Architecture', 'course_level' => 3, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Artificial intellegence', 'course_level' => 3, 'department_ids_string' => 'IS001'],
            ['course_name' => 'IS Project Management', 'course_level' => 3, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Multimedia IS & Digital Libraries', 'course_level' => 3, 'department_ids_string' => 'IS001'],

            // Year 3 Courses (BIO)
            ['course_name' => 'Software Engineering-1', 'course_level' => 3, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Analysis and Design of Algorithms', 'course_level' => 3, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Database Systems-1', 'course_level' => 3, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Modeling & Simulation', 'course_level' => 3, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Biology-1', 'course_level' => 3, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Organic Chemistry', 'course_level' => 3, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Biology-2', 'course_level' => 3, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Biochemistry', 'course_level' => 3, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Genetic Algorithms', 'course_level' => 3, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Mathematical Biology', 'course_level' => 3, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Selected Topics in Computational Biology-1', 'course_level' => 3, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Selected Topics in Computational Biology-2', 'course_level' => 3, 'department_ids_string' => 'BIO001'],

            // Year 3 Courses (AI)
            ['course_name' => 'Software Engineering-1', 'course_level' => 3, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Analysis and Design of Algorithms', 'course_level' => 3, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Database Systems-1', 'course_level' => 3, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Modeling & Simulation', 'course_level' => 3, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Web and Network Programming', 'course_level' => 3, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Artificial intelligence', 'course_level' => 3, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Software Project Management', 'course_level' => 3, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Data Mining and Predictive Analytics', 'course_level' => 3, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Machine Learning', 'course_level' => 3, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Computational Intelligence', 'course_level' => 3, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Advanced Artificial Intelligence', 'course_level' => 3, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Intelligent Agents', 'course_level' => 3, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Knowledge Based Systems', 'course_level' => 4, 'department_ids_string' => 'CS001'],

            // Year 4 Courses (CS)
            ['course_name' => 'Distributed Systems', 'course_level' => 4, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Compiler Design', 'course_level' => 4, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Parallel Programming', 'course_level' => 4, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Computer Security', 'course_level' => 4, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Project', 'course_level' => 4, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Human Computer Interaction', 'course_level' => 4, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Natural Language processing', 'course_level' => 4, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Cloud Computing', 'course_level' => 4, 'department_ids_string' => 'CS001'],
            ['course_name' => 'Robotics', 'course_level' => 4, 'department_ids_string' => 'CS001'],

            // Year 4 Courses (IS)
            ['course_name' => 'Business Intelligence', 'course_level' => 4, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Modern Database Systems', 'course_level' => 4, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Distributed Data Management', 'course_level' => 4, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Cloud Computing', 'course_level' => 4, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Project', 'course_level' => 4, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Geographic IS', 'course_level' => 4, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Big Data Analytics', 'course_level' => 4, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Social Informatics', 'course_level' => 4, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Intelligent IS', 'course_level' => 4, 'department_ids_string' => 'IS001'],
            ['course_name' => 'Molecular & Cell Biology', 'course_level' => 4, 'department_ids_string' => 'IS001'],

            // Year 4 Courses (BIO)
            ['course_name' => 'Neural Networks and Learning Machines', 'course_level' => 4, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Bio-computing', 'course_level' => 4, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Biological Sequence Analysis', 'course_level' => 4, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Bioinformatics', 'course_level' => 4, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Genetics', 'course_level' => 4, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Computational Biology', 'course_level' => 4, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Structural Bioinformatics', 'course_level' => 4, 'department_ids_string' => 'BIO001'],
            ['course_name' => 'Genomics and Proteomics', 'course_level' => 4, 'department_ids_string' => 'BIO001'],

            // Year 4 Courses (AI)
            ['course_name' => 'Distributed and Concurrent Algorithms', 'course_level' => 4, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Natural Language processing', 'course_level' => 4, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Data Analytics Programming', 'course_level' => 4, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Robotics', 'course_level' => 4, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Neural Networks and Learning Machines', 'course_level' => 4, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Project', 'course_level' => 4, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Intelligent Signal Processing', 'course_level' => 4, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Game Development', 'course_level' => 4, 'department_ids_string' => 'AI001'],
            ['course_name' => 'Selected Topics in Artificial Intelligence', 'course_level' => 4, 'department_ids_string' => 'AI001'],

            // Common Project for all
            ['course_name' => 'Project', 'course_level' => 4, 'department_ids_string' => 'CS001-IS001-BIO001-AI001'],
        ];

        foreach ($courseDepartmentData as $data) {
            $course = Course::where('course_name', $data['course_name'])
                            ->where('course_level', $data['course_level'])
                            ->first();

            if ($course) {
                $stringDepartmentIds = explode('-', $data['department_ids_string']);
                $yearValue = $data['course_level']; // القيمة هتكون الـ course_level مباشرة

                foreach ($stringDepartmentIds as $stringDepId) {
                    $numericDepId = $departmentMapping[$stringDepId] ?? null;

                    if ($numericDepId) {
                        $department = Department::find($numericDepId);

                        if ($department) {
                            // ⚠️ هنا التعديل: بنضيف year كمعامل ثاني لـ attach
                            $course->departments()->attach($department->id, ['year' => $yearValue]);
                            $this->command->info("Attached Course '{$course->course_name}' (Level {$course->course_level}, Year: {$yearValue}) to Department '{$department->department_name}' (ID: {$department->id})");
                        } else {
                            $this->command->warn("Numeric Department ID '{$numericDepId}' derived from '{$stringDepId}' not found. Skipping attachment for Course '{$data['course_name']}'.");
                        }
                    } else {
                        $this->command->warn("Mapping for string Department ID '{$stringDepId}' not found. Skipping attachment for Course '{$data['course_name']}'.");
                    }
                }
            } else {
                $this->command->warn("Course '{$data['course_name']}' (Level {$data['course_level']}) not found. Skipping attachment.");
            }
        }
    }
}
