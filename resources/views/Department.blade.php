<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{url('css/all.min.css')}}">
    <link rel="stylesheet" href="{{url('css/normalize.css')}}">
    <link rel="stylesheet" href="{{url('css/Departments.css')}}">
    <title>Departments Page</title>
</head>
<body>
    <div class="main-header">
        <div class="logo"><img src="../images/smart dashboard Logo 4.png" alt="">
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
            <img src="../images/collegue logo.jpg">
        </div>
    </div>
    <div class="main-content">
        <div class="aside">
            <div class="sidebar">
            <a href="{{ route('overview') }}">
                <i class="fa-solid fa-house"></i>
                    <h3>overview</h3>
                </a>
                <a href="{{ route('department.index') }} "class="active">
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
            <h1>Departments</h1>
            <button class="b" onclick="history.back()">Back</button>
        </div>
        <div class="card-container">
            <div class="card">
                <div class="image">
                        <img src="../images/cs.jpg" alt="" style="height: 90px;">
                        <div class="name">
                            <span>(CS)</span>
                            <h2>Computer Science</h2>
                        </div>
                    </div>
                <div class="info">
                    @if ($CS)
                    <p>Head Master: <span class="highlight"> {{ $CS }}</span></p>
                    @else
                    <p>Head Master: <span class="highlight">N/A</span></p>
                    @endif
                    <p>Total Students: <span class="highlight">{{ $cs_student_count }}</span></p>
                </div>
            </div>
            <div class="card">
                <div class="image">
                    <img src="../images/is.webp" alt="">
                    <div class="name">
                        <span>(IS)</span>
                        <h2>Information Systems</h2>
                    </div>
                </div>
                <div class="info">
                    @if ($IS)
                <p>Head Master: <span class="highlight"> {{ $IS }}</span></p>
                @else
                <p>Head Master: <span class="highlight">N/A</span></p>
                @endif
                <p>Total Students: <span class="highlight">{{ $is_student_count }}</span></p>
                </div>
            </div>
            <div class="card">
                <div class="image">
                        <img src="../images/ai.jpg" alt="">
                        <div class="name">
                            <span>(AI)</span>
                            <h2>Artificial Intelligence</h2>
                        </div>
                    </div>
                <div class="info">
                    @if ($AI)
                <p>Head Master: <span class="highlight"> {{ $AI }}</span></p>
                @else
                <p>Head Master: <span class="highlight">N/A</span></p>
                @endif
                <p>Total Students: <span class="highlight">{{ $ai_student_count }}</span></p>
                </div>
            </div>
            <div class="card">
                    <div class="image">
                        <img src="../images/bio.jpg" alt="">
                        <div class="name">
                            <span>(BIO)</span>
                            <h2>Bioinformatics</h2>
                        </div>
                    </div>                <div class="info">
                    @if ($BI)
                <p>Head Master: <span class="highlight"> {{ $BI }}</span></p>
                @else
                <p>Head Master: <span class="highlight">N/A</span></p>
                @endif
                <p>Total Students: <span class="highlight">{{ $bi_student_count }}</span></p>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>
