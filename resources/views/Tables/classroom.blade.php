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
    <link rel="stylesheet" href="{{ url('/css/doc_name.css')}}">
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
                <h1>Classrooms </h1>
                <button class="b" onclick="history.back()">Back</button>
            </div>

            <table class="table">
            <thead class="roooms">
                <tr>
                    <th class="days">Days</th>
                    <th>siminar</th>
                    <th>Inside Plaza</th>
                    <th>Outside Plaza</th>
                    <th>Classroom 1</th>
                    <th>Classroom 2</th>
                    <th>New Classroom</th>
                    <th>cisco</th>
                </tr>
            </thead>
            <tbody class="table-body">
                <tr>
                <th class="day-name">Saturday</th>
                    <td>Dr. Ahmed<br>10:00 - 12:00</td>
                    <td>-</td>
                    <td>Dr. Eman<br>12:00 - 2:00</td>
                    <td>-</td>
                    <td>Eng. Mariam<br>9:00 - 11:00</td>
                    <td>-</td>
                    <td>Eng. Mariam<br>9:00 - 11:00</td>
                </tr>
                <tr>
                    <th class="day-name">Sunday</th>
                    <td>-</td>
                        <td>Dr. Hossam<br>8:00 - 10:00</td>
                        <td>-</td>
                        <td>Dr. Nada<br>11:00 - 1:00</td>
                        <td>-</td>
                        <td>Eng. Youssef<br>10:00 - 12:00</td>
                        <td>-</td>

                </tr>
                <tr>
                <th class="day-name">Monday</th>
                <td>-</td>
                    <td>Dr. Hossam<br>8:00 - 10:00</td>
                    <td>-</td>
                    <td>Dr. Nada<br>11:00 - 1:00</td>
                    <td>-</td>
                    <td>Eng. Youssef<br>10:00 - 12:00</td>
                    <td>-</td>

                </tr>
                <tr>
                <th class="day-name">Tuesday</th>
                <td>-</td>
                    <td>Dr. Hossam<br>8:00 - 10:00</td>
                    <td>-</td>
                    <td>Dr. Nada<br>11:00 - 1:00</td>
                    <td>-</td>
                    <td>Eng. Youssef<br>10:00 - 12:00</td>
                    <td>-</td>

                </tr>
                <tr>
                <th class="day-name">Wednesday</th>
                <td>-</td>
                    <td>Dr. Hossam<br>8:00 - 10:00</td>
                    <td>-</td>
                    <td>Dr. Nada<br>11:00 - 1:00</td>
                    <td>-</td>
                    <td>Eng. Youssef<br>10:00 - 12:00</td>
                    <td>-</td>

                </tr>
                <tr>
                <th class="day-name">Thursday</th>
                <td>-</td>
                    <td>Dr. Hossam<br>8:00 - 10:00</td>
                    <td>-</td>
                    <td>Dr. Nada<br>11:00 - 1:00</td>
                    <td>-</td>
                    <td>Eng. Youssef<br>10:00 - 12:00</td>
                    <td>-</td>

                </tr>
            </tbody>
        </table>

        </div>

    </div>
</body>


</html> --}}
