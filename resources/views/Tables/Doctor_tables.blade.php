<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- تأكد من وجود الـ CSRF token meta tag إذا كنت ستستخدم AJAX أو Form POST --}}
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ url('/css/all_min.css')}}">
    <link rel="stylesheet" href="{{ url('/css/normalize.css')}}">
    <link rel="stylesheet" href="{{ url('/css/Doctor_tables.css')}}">
    <title>Instructors Tables</title> {{-- عنوان عام للصفحة --}}

    <style>
        /* CSS for the table structure */
        .table-container {
            margin-top: 30px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow-x: auto; /* Ensures table is responsive */
        }

        .instructor-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .instructor-table thead tr {
            background-color: #007bff; /* Primary blue for header */
            color: #ffffff;
            text-align: left;
            font-weight: bold;
        }

        .instructor-table th,
        .instructor-table td {
            padding: 12px 15px;
            border: 1px solid #e0e0e0; /* Lighter border for cells */
        }

        .instructor-table tbody tr {
            border-bottom: 1px solid #f0f0f0; /* Subtle row separator */
        }

        .instructor-table tbody tr:nth-of-type(even) {
            background-color: #f9f9f9; /* Zebra striping */
        }

        .instructor-table tbody tr:hover {
            background-color: #f1f1f1; /* Light hover effect */
            transition: background-color 0.2s ease;
        }

        /* Styling for the links (instructor names) */
        .instructor-table td a {
            color: #007bff; /* Blue link color */
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease, text-decoration 0.2s ease;
        }

        .instructor-table td a:hover {
            color: #0056b3; /* Darker blue on hover */
            text-decoration: underline;
        }

        /* Styling for position column */
        .instructor-table .position-cell {
            font-style: italic;
            color: #555;
        }

        /* Styling for work days column */
        .instructor-table .work-days-cell {
            font-size: 0.9em;
            color: #666;
        }

        /* Message for no data */
        .no-data-message {
            text-align: center;
            padding: 20px;
            color: #777;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="main-header">
        <div class="logo"><img src="{{ asset('images/smart dashboard Logo 4.png') }}" alt="">
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
            <img src="{{ asset('images/collegue logo.jpg') }}">
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
                <a href="{{ route('instructor') }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>instructor</h3>
                </a>

                <a href="{{route('tables')}}" class="active">
                    <i class="fa-solid fa-table"></i>
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
                {{-- نتحقق إذا كان المتغير 'Doc' يحتوي على بيانات لتحديد العنوان --}}
                @if(isset($Doc) && $Doc->isNotEmpty())
                    @php
                        // نفترض أن كل الكائنات في $Doc لها نفس الـ 'position'
                        $firstInstructorPosition = $Doc->first()->position ?? '';
                        $pageTitle = ($firstInstructorPosition === 'Professor') ? 'Doctors Tables' : 'Assistants Tables';
                    @endphp
                    <h1>{{ $pageTitle }}</h1>
                @else
                    <h1>Instructors Tables</h1> {{-- عنوان افتراضي إذا لم يكن هناك بيانات --}}
                @endif
                <button class="b" onclick="history.back()">Back</button>
            </div>


            <div class="table-container">
                <table class="instructor-table">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Position</th> {{-- إضافة عمود للمنصب --}}
                            <th>Work Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($Doc as $instructor) {{-- استخدام @forelse للتعامل مع الحالة الفارغة --}}
                        <tr>
                            <td>
                                {{-- هنا يكون الاسم رابطًا لصفحة الجدول الزمني الفردي --}}
                                <a href="{{ route('instructorTable', ['id' => $instructor->id]) }}">
                                    {{ $instructor->full_name }}
                                </a>
                            </td>
                            <td class="position-cell">
                                @if ($instructor->position === 'Professor')
                                    Professor
                                @elseif ($instructor->position === 'Assistant')
                                    Assistant
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="work-days-cell">
                                {{-- افترض أن work_days مخزنة كـ JSON string أو مصفوفة --}}
                                @if(is_array($instructor->work_days))
                                    {{ implode(', ', $instructor->work_days) }}
                                @else
                                    {{ $instructor->work_days ?? 'Not specified' }} {{-- إذا كانت نصًا عاديًا --}}
                                @endif
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="no-data-message">No instructors found in this category.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
