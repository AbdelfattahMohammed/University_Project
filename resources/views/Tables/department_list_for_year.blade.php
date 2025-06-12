<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ url('/css/all_min.css') }}">
    <link rel="stylesheet" href="{{ url('/css/normalize.css') }}">
    <link rel="stylesheet" href="{{ url('/css/L1_timetable.css') }}">
    <title>tables</title>

    <style>
        /* إضافة بعض الأنماط الأساسية لمواءمة التصميم الجديد مع القديم */
        body {
            font-family: 'Arial', sans-serif; /* استخدم خطًا أكثر شيوعًا إذا لم يكن لديك 'Segoe UI' */
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column; /* لجعل المحتوى يتدفق عمودياً */
        }
        .main-content {
            display: flex;
            flex-grow: 1; /* لجعل المحتوى يأخذ المساحة المتبقية */
        }
        .holder {
            flex-grow: 1;
            padding: 20px;
            background-color: #fff;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 2.2rem;
            color: #333;
            margin: 0;
        }
        .b {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .b:hover {
            background-color: #0056b3;
        }

        .department-section {
            background-color: #fdfdfd;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }
        .department-section .cap {
            font-size: 1.8rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: right;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
            display: block; /* لجعل العنوان يأخذ سطرًا كاملاً */
        }
        .no-timetable-message {
            text-align: center;
            padding: 40px 20px;
            background-color: #f9f9f9;
            border: 1px dashed #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .no-timetable-message p {
            font-size: 1.3rem;
            color: #555;
            margin-bottom: 25px;
        }
        .action-button {
            display: inline-block;
            background-color: #28a745; /* Green for create */
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 7px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
            cursor: pointer;
        }
        .action-button:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        .action-button.edit {
            background-color: #007bff; /* Blue for edit */
        }
        .action-button.edit:hover {
            background-color: #0056b3;
        }
        .table-con {
            overflow-x: auto; /* للسماح بالتمرير الأفقي للجداول الكبيرة */
            margin-top: 20px; /* مسافة بين العنوان والجداول */
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            vertical-align: middle;
            font-size: 0.9rem;
        }
        .table thead th {
            background-color: #e2e8f0;
            color: #333;
            font-weight: 700;
        }
        .table tbody th.day-name {
            background-color: #f0f4f7;
            font-weight: 600;
            color: #555;
        }
        .table-body td span {
            font-weight: bold;
            color: #333;
        }
        .table-body td p {
            margin: 0;
            line-height: 1.4;
            color: #666;
        }
        .table-body td.empty-slot {
            background-color: #f5f5f5; /* لون خلفية للخانة الفارغة */
            color: #aaa;
            font-style: italic;
        }
        .table-actions {
            text-align: center;
            margin-top: 20px;
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
                <h1>level {{ $year }} </h1>
                <button class="b" onclick="history.back()">Back</button>
            </div>

            <div class="table-con">
                @forelse ($departmentTimetableData as $data)
                    <div class="department-section">
                        <caption class="cap">{{ $data['department']->department_name }}</caption>

                        @if ($data['hasTimetable'])
                            <table class="table">
                                <thead class="roooms">
                                    <tr>
                                        <th class="days">الأيام / الفترات</th>
                                        @foreach ($periods as $periodNum => $periodTime)
                                            <th>{{ $periodTime }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="table-body">
                                    @foreach ($days as $day)
                                        <tr>
                                            <th class="day-name">{{ $day }}</th>
                                            @foreach ($periods as $periodNum => $periodTime)
                                                @php
                                                    $slot = $data['timetableGrid'][$day][$periodNum] ?? null;
                                                @endphp
                                                <td @if(!$slot) class="empty-slot" @endif>
                                                    @if ($slot)
                                                        <span>{{ $slot->course->course_name ?? 'N/A' }}</span>
                                                        <br>
                                                        @if ($slot->professor)
                                                            د. {{ $slot->professor->full_name }}
                                                        @endif
                                                        @if ($slot->assistant)
                                                            <br>م. {{ $slot->assistant->full_name }}
                                                        @endif
                                                        @if ($slot->room)
                                                            <br>قاعة: {{ $slot->room->room_name }}
                                                        @endif
                                                    @else
                                                        لا يوجد حصة
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="table-actions">
                                <a href="{{ route('timetables.manage_grid', ['year' => $year, 'department_id' => $data['department']->id]) }}"
                                   class="action-button edit">
                                    <i class="fas fa-edit"></i> تعديل الجدول
                                </a>
                            </div>
                        @else
                            <div class="no-timetable-message">
                                <p>لا يوجد جدول حالي لقسم {{ $data['department']->department_name }} في السنة {{ $year }}.</p>
                                <a href="{{ route('timetables.manage_grid', ['year' => $year, 'department_id' => $data['department']->id]) }}"
                                   class="action-button">
                                    <i class="fas fa-plus-circle"></i> إنشاء جدول جديد
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-gray-700 text-lg">لا توجد أقسام متاحة لعرض الجداول في هذه السنة.</p>
                @endforelse
            </div>
        </div>

    </div>
</body>


</html>
