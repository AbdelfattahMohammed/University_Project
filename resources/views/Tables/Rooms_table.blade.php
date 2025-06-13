<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ url('/css/all_min.css') }}">
    <link rel="stylesheet" href="{{ url('/css/normalize.css') }}">
    <link rel="stylesheet" href="{{ url('/css/Doctor_tables.css') }}"> {{-- يمكن استخدام نفس ملف الستايل أو إنشاء واحد جديد --}}
    <title>Classroom Availability Table</title>

    <style>
        /* Specific styling for Rooms_table */
        .rooms-table-container {
            overflow-x: auto;
        }

        .rooms-availability-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            min-width: 800px;
            /* Adjust as needed for horizontal scrolling */
        }

        .rooms-availability-table th,
        .rooms-availability-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            vertical-align: top;
            /* Align content to top for better readability of multiple items */
        }

        .rooms-availability-table thead th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
        }

        .rooms-availability-table tbody td {
            background-color: #fff;
        }

        .rooms-availability-table .room-name-cell {
            font-weight: bold;
            text-align: left;
            background-color: #e9ecef;
        }

        .rooms-availability-table .busy-periods {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .rooms-availability-table .busy-periods li {
            margin-bottom: 5px;
            padding: 5px;
            background-color: #ffcccc;
            /* Light red for busy slots */
            border-radius: 4px;
            font-size: 0.9em;
            text-align: left;
            border: 1px solid #ff9999;
        }

        .rooms-availability-table .available-slot {
            color: #28a745;
            /* Green for available */
            font-weight: bold;
        }

        .no-rooms-message {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            background-color: #e9ecef;
            border-radius: 8px;
            margin-top: 20px;
        }
    </style>
    {{-- إضافة دالة translateDayToArabic هنا لتستخدمها في الـ Blade --}}
    @php
        function translateDayToArabic(string $dayName): string
        {
            switch ($dayName) {
                case 'Saturday':
                    return 'السبت';
                case 'Sunday':
                    return 'الأحد';
                case 'Monday':
                    return 'الاثنين';
                case 'Tuesday':
                    return 'الثلاثاء';
                case 'Wednesday':
                    return 'الأربعاء';
                case 'Thursday':
                    return 'الخميس';
                case 'Friday':
                    return 'الجمعة'; // لن يتم استخدام الجمعة في هذا الجدول ولكن من الجيد وجودها
                default:
                    return $dayName;
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

                <a href="{{ route('tables') }}" class="active">
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
                <h1>Classroom Availability</h1>
                <button class="b" onclick="history.back()">Back</button>
            </div>

            @if ($rooms->isEmpty())
                <p class="no-rooms-message">No classrooms found to display.</p>
            @else
                <div class="rooms-table-container">
                    <table class="rooms-availability-table">
                        <thead>
                            <tr>
                                <th>Classroom Name</th>
                                @foreach ($daysOfWeek as $day)
                                    <th>{{ translateDayToArabic($day) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rooms as $room)
                                <tr>
                                    <td class="room-name-cell">{{ $room->room_name }}</td>
                                    @foreach ($daysOfWeek as $day)
                                        <td>
                                            @php
                                                $busyPeriods =
                                                    $formattedRoomTimetable[$room->id]['schedule'][$day] ?? [];
                                                ksort($busyPeriods); // لفرز الفترات بترتيبها
                                            @endphp

                                            @if (empty($busyPeriods))
                                                <span class="available-slot">Available</span>
                                            @else
                                                <ul class="busy-periods">
                                                    @foreach ($busyPeriods as $periodNum => $data)
                                                        <li>
                                                            <strong>P{{ $periodNum }}
                                                                ({{ $data['period_time'] }}):</strong>
                                                            {{ $data['details'] }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</body>

</html>
























{{-- <!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ url('/css/all_min.css')}}">
    <link rel="stylesheet" href="{{ url('/css/normalize.css')}}">
    <link rel="stylesheet" href="{{ url('/css/rooms_tables.css')}}">
    <title>tables</title>

</head>

<body>
    <div class="main-header">
        <div class="logo"><img src="{{ asset('images/smart dashboard Logo 4.png') }}" alt="">
            <div class="text">
                <p ><span>smart</span>
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
                <a href="{{ route('student.index') }}" >
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
                <h1>Tables</h1>
                <button class="b" onclick="history.back()">Back</button>
            </div>

<div class="container my-5">
    <h2 class="mb-4 text-center">Lecture Schedule</h2>
    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Room</th>
                    <th>Saturday</th>
                    <th>Sunday</th>
                    <th>Monday</th>
                    <th>Tuesday</th>
                    <th>Wednesday</th>
                    <th>Thursday</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Room A</th>
                    <td>Dr. Ahmed<br>10:00 - 12:00</td>
                    <td>-</td>
                    <td>Dr. Eman<br>12:00 - 2:00</td>
                    <td>-</td>
                    <td>Eng. Mariam<br>9:00 - 11:00</td>
                    <td>-</td>
                </tr>
                <tr>
                    <th>Room B</th>
                    <td>-</td>
                    <td>Dr. Hossam<br>8:00 - 10:00</td>
                    <td>-</td>
                    <td>Dr. Nada<br>11:00 - 1:00</td>
                    <td>-</td>
                    <td>Eng. Youssef<br>10:00 - 12:00</td>
                </tr>
                <!-- Add more rooms -->
            </tbody>
        </table>
    </div>
</div>


        </div>
    </div>
</body>


</html> --}}
