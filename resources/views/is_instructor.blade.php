<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ url('css/all.min.css') }}">
    <link rel="stylesheet" href="{{ url('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ url('css/is_instructor.css') }}">
    <title>IS instructors Page</title>

</head>

<body>
    <div class="main-header">
        <div class="logo"><img src="{{ url('/images/smart dashboard Logo 4.png') }}" alt="">
            <div class="text">
                <p><span>smart</span>
                    <span>dashboard</span>
                </p>
            </div>
        </div>

        <div class="form">
            <form action="{{ route('search') }}" method="GET">
                <input type="text" name="query" placeholder="Search" required>
                <button type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>
        <div class="fac-img">
            <img src="{{ url('/images/collegue logo.jpg') }}">
        </div>
    </div>

    <div class="main-content">
        <div class="aside">
            <div class="sidebar">
                <a href="{{ route('overview') }}">
                    <i class="fa-solid fa-house"></i>
                    <h3>overview</h3>
                </a>
                <a href="{{ route('department.index') }}">
                    <i class="fa-solid fa-database"></i>
                    <h3>departments</h3>
                </a>
                <a href="{{ route('courses.index') }}">
                    <i class="fas fa-book-open"></i>
                    <h3>Courses</h3>
                </a>
                <a href="{{ route('student.index') }}">
                    <i class="fas fa-user-graduate"></i>
                    <h3>Students</h3>
                </a>
                <a href="{{ route('instructor') }}" class="active">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>instructor</h3>
                </a>

                <a href="{{ route('tables') }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>tables</h3>
                </a>

                <a href="{{ route('attendance.index') }}">
                    <i class="fa-solid fa-clipboard-user"></i>
                    <h3>attendance</h3>
                </a>
                <a href="{{ route('financial.financial') }}">
                    <i class="fa-solid fa-receipt"></i>
                    <h3>financial reports</h3>
                </a>
                <a href="{{ route('event.index') }}">
                    <i class="fa-solid fa-calendar-days"></i>
                    <h3>event</h3>
                </a>
            </div>
        </div>
        <div class="holder">
            <div class="header">
                {{-- لعرض اسم القسم في العنوان --}}
                <h1>Instructors for {{ $department->department_name ?? 'Department' }}</h1>
                <button class="b" onclick="history.back()">Back</button>
            </div>
            <div>
                <h3>Head of Department: <span class="name">{{ $headOfDepartment }}</span></h3>

                {{-- يمكن إضافة فحص @if ($instructors->isEmpty()) هنا لإظهار رسالة إذا لم يكن هناك مدرسون --}}

                <table class="table table-bordered">
                    {{-- Caption يمكن أن يكون ديناميكيًا --}}
                    <caption>{{ $department->department_name ?? 'Department' }} Instructors</caption>
                    <thead class="table-dark">
                        <tr>
                            <th>Full Name</th>
                            <th>Position</th> {{-- أضفت هذا العمود لعرض الوظيفة --}}
                            <th>Courses Taught in This Department</th>
                            <th>Work Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($instructors as $instructor)
                            <tr>
                                <td>{{ $instructor->full_name }}</td>
                                <td>{{ $instructor->position ?? 'N/A' }}</td> {{-- عرض الوظيفة --}}
                                <td>
                                    {{-- هنا التعديل الرئيسي: يجب أن تقوم بحلقة على المقررات --}}
                                    @if ($instructor->courses->isNotEmpty())
                                        <ul class="list-unstyled"> {{-- استخدم list-unstyled لإزالة التنسيق الافتراضي للقائمة --}}
                                            @foreach ($instructor->courses as $course)
                                                <li>{{ $course->course_name }} ({{ $course->course_code ?? '' }})</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">No courses assigned in this department.</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- تحويل أيام العمل من سلسلة نصية إلى قائمة مقروءة --}}
                                    @if (!empty($instructor->work_days))
                                        @php
                                            // افترض أن work_days هو سلسلة نصية مفصولة بفاصلة أو يتم تحويلها إلى مصفوفة
                                            $workDaysArray = json_decode($instructor->work_days, true); // إذا كانت JSON
                                            if (!is_array($workDaysArray)) {
                                                $workDaysArray = explode(',', $instructor->work_days); // إذا كانت مفصولة بفاصلة
                                            }
                                        @endphp
                                        @if (!empty($workDaysArray))
                                            <ul class="list-unstyled">
                                                @foreach ($workDaysArray as $day)
                                                    <li>{{ trim($day) }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            N/A
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No instructors found for this department.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</body>




</html>
