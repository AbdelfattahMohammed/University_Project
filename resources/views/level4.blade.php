<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="css/all.min.css">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <title>Level 4 Subjects</title>
  <style>
  body {
    scroll-behavior: smooth;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

:root {
    --maincolor: #2196f3;
    --maincolor-alt: #1787e0;
    --main-padding: 100px;
    --main-transition-dur: 0.3s;
    --section-color: #ececec;
}

    .subject {
      background-color: lightgray;
      padding: 10px;
      margin: 10px auto;
      width: 55%;
      border-radius: 10px;
    }



    .main-header {
      position: relative;
      box-shadow: 0px -10px 17px black;
      display: flex;
      justify-content: space-between;
      align-items: center;
      overflow: hidden;
    }

    .main-header .logo {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .main-header .logo img {
      max-width: 100%;
      height: 60px;
      width: 90px;
    }

    .main-header .text p {
      text-transform: capitalize;
      font-size: 35px;
      font-weight: bold;
    }

    .main-header p span:first-child {
      color: #004aad;
    }

    .main-header p span:last-child {
      color: #00bf63;
    }

    .main-header .form {
      position: relative;
      overflow: hidden;
    }

    .main-header .form input {
  width: 300px;
  height: 30px;
  padding: 8px;
  border: 1px solid black;
  outline: none;
}

.main-header .form button {
  position: absolute;
  right: 0px;
  top: 50%;
  transform: translateY(-50%);
  background-color: var(--maincolor);
  width: 35px;
  height: 100%;
  border: 1px solid black;
}
    .main-header .form button:hover {
      cursor: pointer;
    }

    .main-header .form button i {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .main-header .fac-img img {
      max-width: 100%;
      height: 60px;
      width: 90px;
    }

    /* --------------------------------- */
    .main-content {
      overflow: hidden;
      min-height: 100vh;
      background-color: #f9f9f9;
      display: flex;
      justify-content: center;
    }

    .sidebar {
      padding: 10px 0;
      height: 100%;
      display: flex;
      flex-direction: column;
      gap: 10PX;
      box-shadow: -4PX -4PX 13PX black;
    }

    .sidebar a {
      text-transform: capitalize;
      display: flex;
      align-items: center;
      padding: 20px 10PX;
      text-decoration: none;
      color: #333;
      padding-right: 53px;
      transition: background-color 0.3s;
      /* Smooth hover effect */
    }

    .sidebar a:hover {
      background-color: #f0f0f0;
      color: #007bff;
    }

    .sidebar a.active {
      background-color: #e0e0e0;
      /* Slightly darker for active */
      color: #007bff;
      /* Blue for active text */
    }

    .sidebar a i {
      margin-right: 10px;
    width: 20px;
    text-align: center;
    }

/* .sidebar h3 {
    margin-top: -8px;
    font-size: 18px;
    font-weight: bold;
} */

    .holder {
      /* flex-grow: 1; */
      padding: 20px;
      flex: 1;
    }

    .holder .header {
        background: #ecf0f1;
    padding: 10px;
    border-bottom: 2px solid #bdc3c7;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    }

    .holder .header h1 {
      font-size: 30px;
      color: #27ae60;
    }



    .holder .header button {
    padding: 10px 20px;
    background-color: #4caf50;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
  </style>
</head>

<body>
  <div class="main-header">
    <div class="logo">
        <img src="{{url ('/images/smart dashboard Logo 4.png') }}" alt="">
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
      <img src="{{url ('/images/collegue logo.jpg') }}" alt="College Logo">
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
        <h1>Courses</h1>
        <button class="b" onclick="history.back()">Back</button>
      </div>

      <div class="container">
        <h2 class="text-center mb-4">Level 4 - Semester 1 Courses</h2>

        <div class="row">
            @forelse($courses as $departmentId => $courseList)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Department: {{ $courseList->isNotEmpty() ? $courseList->first()->department->department_name : 'Unknown Department' }}</h5>
                        </div>
                        <div class="card-body">
                            @if($courseList->isNotEmpty())
                                <ul class="list-group">
                                    @foreach($courseList as $course)
                                        <li class="list-group-item">
                                            <strong>{{ $course->course_name }}</strong>

                                            <!-- Display instructors -->
                                            @if($course->instructors->isNotEmpty())
                                                <ul class="mt-2">
                                                    @foreach($course->instructors as $instructor)
                                                        <li class="text-muted">{{ $instructor->full_name }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-danger small">No instructors assigned</p>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-danger text-center">No courses available for this department.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-danger text-center">No departments have courses available.</p>
                </div>
            @endforelse
        </div>
    </div>


</body>

</html>
