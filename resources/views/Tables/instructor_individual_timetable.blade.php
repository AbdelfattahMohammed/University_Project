<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ url('/css/all_min.css')}}">
    <link rel="stylesheet" href="{{ url('/css/normalize.css')}}">
    <link rel="stylesheet" href="{{ url('/css/Doctor_tables.css')}}">
    <title>Timetable for {{ $instructor->full_name }}</title>

    <style>
        /* General styling for the page layout (from your main template) */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-header {
            background-color: #fff;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .main-header .logo img {
            height: 50px;
        }

        .main-header .form input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .main-content {
            display: flex;
            flex-grow: 1;
        }

        .aside {
            width: 200px;
            background-color: #333;
            color: #fff;
            padding: 20px;
            flex-shrink: 0;
        }

        .aside a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px 0;
            margin-bottom: 10px;
        }

        .holder {
            flex-grow: 1;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .holder .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .holder .header h1 {
            color: #333;
            margin: 0;
        }

        .holder .header .b {
            background-color: #007bff;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .holder .header .b:hover {
            background-color: #0056b3;
        }


        /* Specific styling for the individual timetable */
        .year-section {
            background-color: #e0f7fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .year-section h2 {
            color: #007bff;
            margin-top: 0;
            margin-bottom: 15px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }

        .department-section {
            background-color: #ffffff;
            border: 1px solid #cce5ff;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .department-section h3 {
            color: #333;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 1.2em;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 5px;
        }

        .timetable-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .timetable-table th,
        .timetable-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .timetable-table thead th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
        }

        .timetable-table tbody td {
            background-color: #fff;
        }

        .timetable-table .empty-slot {
            color: #aaa;
            font-style: italic;
        }

        .timetable-table .course-name {
            font-weight: bold;
            color: #007bff;
        }

        .timetable-table .room-name {
            font-size: 0.9em;
            color: #555;
        }

        .no-classes-message {
            text-align: center;
            padding: 15px;
            color: #dc3545;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
    {{-- تم تغيير اسم الدالة هنا --}}
    @php
        function translateDayToArabic(string $dayName): string
        {
            switch ($dayName) {
                case 'Saturday': return 'السبت';
                case 'Sunday': return 'الأحد';
                case 'Monday': return 'الاثنين';
                case 'Tuesday': return 'الثلاثاء';
                case 'Wednesday': return 'الأربعاء';
                case 'Thursday': return 'الخميس';
                case 'Friday': return 'الجمعة';
                default: return $dayName;
            }
        }
    @endphp
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
                <h1>Timetable for {{ $instructor->full_name }} ({{ $instructor->position }})</h1>
                <button class="b" onclick="history.back()">Back</button>
            </div>

            @forelse ($formattedTimetable as $year => $departments)
                <div class="year-section">
                    <h2>Year: {{ $year }}</h2>
                    @forelse ($departments as $departmentName => $daysData)
                        <div class="department-section">
                            <h3>Department: {{ $departmentName }}</h3>
                            <table class="timetable-table">
                                <thead>
                                    <tr>
                                        <th>Day / Period</th>
                                        @foreach ($periodsMap as $periodNum => $periodTime)
                                            <th>Period {{ $periodNum }} <br>({{ $periodTime }})</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($daysOfWeek as $day)
                                        <tr>
                                            <td>{{ translateDayToArabic($day) }}</td> {{-- تم تغيير اسم الدالة هنا --}}
                                            @foreach (array_keys($periodsMap) as $periodNum)
                                                <td>
                                                    @php
                                                        $slot = $daysData[$day][$periodNum];
                                                    @endphp
                                                    @if ($slot['course'])
                                                        <p class="course-name">{{ $slot['course'] }}</p>
                                                        <p class="room-name">Room: {{ $slot['room'] }}</p>
                                                        <small>({{ $slot['instructor_type'] }})</small>
                                                    @else
                                                        <p class="empty-slot">Empty</p>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @empty
                        <p class="no-classes-message">No classes found for this instructor in this year across any department.</p>
                    @endforelse
                </div>
            @empty
                <p class="no-classes-message">No timetable data found for this instructor.</p>
            @endforelse

        </div>
    </div>
</body>

</html>
