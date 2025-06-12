<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h2 class="mb-4">Search Results for "{{ $query }}"</h2>

        @if($students->isEmpty() && $instructors->isEmpty() && $departments->isEmpty() && $courses->isEmpty() &&
           $events->isEmpty() && $financials->isEmpty() && // إضافة financial
           $attendance->isEmpty() && $rooms->isEmpty() && $timetables->isEmpty())
            <div class="alert alert-warning">لا توجد نتائج بحث مطابقة.</div>
        @else
            <div class="row">
                {{-- Students --}}
                @if(!$students->isEmpty())
                    <div class="col-md-6 mb-4">
                        <h3>Students</h3>
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Year</th>
                                    <th>Gender</th>
                                    <th>Department</th>
                                    <th>GPA</th>
                                    <th>Address</th>
                                    <th>Phone</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->year }}</td>
                                        <td>{{ $student->gender }}</td>
                                        <td>{{ $student->department->department_name ?? 'N/A' }}</td>
                                        <td>{{ $student->gpa }}</td>
                                        <td>{{ $student->address }}</td>
                                        <td>{{ $student->phone }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

{{-- Instructors --}}
                @if(!$instructors->isEmpty())
                    <div class="col-md-6 mb-4">
                        <h3>Instructors</h3>
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Full Name</th>
                                    <th>Position</th>
                                    <th>Department</th> {{-- هذا العمود سيظهر اسم القسم --}}
                                    <th>Work Days</th>
                                    <th>Courses</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($instructors as $instructor)
                                    <tr>
                                        <td>{{ $instructor->full_name }}</td>
                                        <td>{{ $instructor->position }}</td>
                                        {{-- **هنا التعديل المهم:** استخدام 'department' (مفرد) --}}
                                        <td>{{ $instructor->department->department_name ?? 'N/A' }}</td>
                                        <td>{{ $instructor->work_days }}</td>
                                        <td>
                                            @if($instructor->courses->isNotEmpty())
                                                <ul class="list-unstyled mb-0">
                                                    @foreach($instructor->courses as $course)
                                                        <li>{{ $course->course_name }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="row mt-4">
                {{-- Departments --}}
                @if(!$departments->isEmpty())
                    <div class="col-md-6 mb-4">
                        <h3>Departments</h3>
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Department Name</th>
                                    <th>Head of Department</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departments as $department)
                                    <tr>
                                        <td>{{ $department->department_name }}</td>
                                        <td>{{ $department->head_of_department }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Courses --}}
                @if(!$courses->isEmpty())
                    <div class="col-md-6 mb-4">
                        <h3>Courses</h3>
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Course Name</th>
                                    <th>Level</th>
                                    <th>Term</th>
                                    <th>Departments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $course)
                                    <tr>
                                        <td>{{ $course->course_name }}</td>
                                        <td>{{ $course->course_level }}</td>
                                        <td>{{ $course->term }}</td>
                                        <td>
                                            @if($course->departments->isNotEmpty())
                                                <ul class="list-unstyled mb-0">
                                                    @foreach($course->departments as $dept)
                                                        <li>{{ $dept->department_name }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="row mt-4">
                {{-- Events --}}
                @if(!$events->isEmpty())
                    <div class="col-md-6 mb-4">
                        <h3>Events</h3>
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Event Name</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                    <tr>
                                        <td>{{ $event->event_name }}</td>
                                        <td>{{ $event->event_date }}</td>
                                        <td>{{ $event->type }}</td>
                                        <td>{{ $event->location ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Financials (New) --}}
                @if(!$financials->isEmpty())
                    <div class="col-md-6 mb-4">
                        <h3>Financial Data</h3>
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Department</th>
                                    <th>Year</th>
                                    <th>Revenue</th>
                                    <th>Expenses</th>
                                    <th>Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($financials as $financial)
                                    <tr>
                                        <td>{{ $financial->department }}</td>
                                        <td>{{ $financial->year }}</td>
                                        <td>${{ number_format($financial->revenue, 2) }}</td>
                                        <td>${{ number_format($financial->expenses, 2) }}</td>
                                        <td>${{ number_format($financial->profit, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>

            <div class="row mt-4">

                {{-- Student Attendance --}}
                @if(!$attendance->isEmpty())
                    <div class="col-md-6 mb-4">
                        <h3>Student Attendance</h3>
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Student Name</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendance as $record)
                                    <tr>
                                        <td>{{ $record->student->name ?? 'N/A' }}</td>
                                        <td>{{ $record->attendance_date }}</td>
                                        <td>{{ $record->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Rooms --}}
                @if(!$rooms->isEmpty())
                <div class="col-md-6 mb-4">
                    <h3>Rooms</h3>
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Room Name</th>
                                <th>Capacity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rooms as $room)
                            <tr>
                                <td>{{ $room->room_name }}</td>
                                <td>{{ $room->capacity ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            <div class="row mt-4">
                {{-- Timetables --}}
                @if(!$timetables->isEmpty())
                <div class="col-12 mb-4"> {{-- عرض الجدول الزمني على عرض كامل للصفحة --}}
                    <h3>Timetable Entries</h3>
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Day</th>
                                    <th>Period</th>
                                    <th>Department</th>
                                    <th>Year</th>
                                    <th>Course</th>
                                    <th>Professor</th>
                                    <th>Assistant</th>
                                    <th>Room</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($timetables as $entry)
                                    <tr>
                                        <td>{{ $entry->day }}</td>
                                        <td>{{ $entry->period_number }}</td>
                                        <td>{{ $entry->department->department_name ?? 'N/A' }}</td>
                                        <td>{{ $entry->year }}</td>
                                        <td>{{ $entry->course->course_name ?? 'N/A' }}</td>
                                        <td>{{ $entry->professor->full_name ?? 'N/A' }}</td>
                                        <td>{{ $entry->assistant->full_name ?? 'N/A' }}</td>
                                        <td>{{ $entry->room->room_name ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
