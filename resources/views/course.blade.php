<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="{{ url('/css/all_min.css')}}">
        <link rel="stylesheet" href="{{ url('/css/normalize.css')}}">
        <link rel="stylesheet" href="{{ url('/css/course.css')}}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <title>Semester Hover Effect</title>
</head>
<body>

    <div class="main-header">
        <div class="logo">
        <img src="../images/smart dashboard Logo 4.png" alt="">
        <div class="text">
            <p><span>smart</span><span> Dashboard</span></p>
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
        <img src="../images/collegue logo.jpg" alt="College Logo">
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
                <a href="{{ route('courses.index') }}" class="active">
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
                <h1>Courses </h1>
                <button class="b" onclick="history.back()">Back</button>
            </div>
            <div class="container">
                <ul class="levels-ul">
                    <li class="li-levels">Level-1
                        <ul>
                            <li><a href="{{ route('courses.semester1level1') }}" class="semester">Semester 1</a></li>
                            <li><a href="{{ route('courses.semester2level1') }}" class="semester">Semester 2</a></li>
                        </ul>
                    </li>
                    <li  class="li-levels">Level-2
                        <ul>
                            <li><a href="{{ route('courses.semester1level2') }}" class="semester">Semester 1</a></li>
                            <li><a href="{{ route('courses.semester2level2') }}" class="semester">Semester 2</a></li>
                        </ul>
                    </li>
                    <li  class="li-levels">Level-3
                        <ul>
                            <li><a href="{{ route('courses.semester1level3') }}" class="semester">Semester 1</a></li>
                            <li><a href="{{ route('courses.semester2level3') }}" class="semester">Semester 2</a></li>
                        </ul>
                    </li>
                    <li  class="li-levels">Level-4
                        <ul>
                            <li><a href="{{ route('courses.semester1level4') }}" class="semester">Semester 1</a></li>
                            <li><a href="{{ route('courses.semester2level4') }}" class="semester">Semester 2</a></li>
                        </ul>
                    </li>
                </ul>
                
                
            </div>
        </div>
    </div>
</body>

</html>
