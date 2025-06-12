{{-- <!DOCTYPE html>
<html lang="ar">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="css/all.min.css">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <title>Levels</title>
  <style>
   .box {
      width: 100%;
      height: 80px;
      border-radius: 13px;
      margin-top: 20px;
      justify-content: space-between;
      background-color: rgb(255, 246, 246);
      color: rgb(46, 27, 27);
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 20px;
      font-weight: bold;
      text-decoration: none;
      transition: width 0.3s, height 0.3s;
    }







    .all {
      display: block;
      width: 100%;

    }

    /* --------------------------------------- */
    .collect {
      width: 300px;
      height: 400px;
      /* background-color: #4497f1; */
      margin: auto;

    }

    /* ------------------------- */
    .content {
      overflow: hidden;
      display: grid;
      grid-template-columns: 250px 1fr 300px;
      /* Sidebar, Main, Right Section */
      gap: 1rem;
      height: calc(100vh - 80px);
    }

    /* ------------------------------------------------------- */

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
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
      border-radius: 20px;
      width: 300px;
      height: 30px;
      padding: 8px;
      border: 1px solid black;
    }

    .main-header .form button {
      position: absolute;
      right: 0;
      top: 50%;
      padding: 8px 20px;
      transform: translateY(-50%);
      background-color: #4497f1;
      border: none;
      width: 35px;
      border-radius: 45%;
      height: 100%;
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
      /* Consistent icon size */
      text-align: center;
      /* Center icons */
    }


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


    .card-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
    align-items: center;
}

.card-container,
.card,
a {
    text-decoration: none;

}

.card {
    background-image: linear-gradient(135deg,green,white) ;
    padding: 35px;
    border-radius: 8px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 250px;
}

.card:hover {
    transform: scale(1, 1.1) translateX(-10px);
    cursor: pointer;
}

.card h2 {
    font-size: 30px;
    color: #2c3e50;
}



.card .highlight {
    font-weight: bold;
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
      <div class="card-container" >
        <div style="display: flex; justify-content: space-between; margin: 50px 0 100px 0; ">
            <div class="card">
                <a href="{{ route('courses.semester2level1') }}">
                    <h2>Level-1</h2>
                </a>
            </div>
            <div class="card" style="margin-left: 200px; ">
                <a href="{{ route('courses.semester2level2') }}">
                    <h2>Level-2</h2>
                </a>
            </div>
        </div>
        <div style="display: flex;">
            <div class="card">
                <a href="{{ route('courses.semester2level3') }}">
                    <h2>Level-3</h2>
                </a>

            </div>
            <div class="card" style="margin-left: 200px; ">
                <a href="{{ route('courses.semester2level4') }}">
                    <h2>Level-4</h2>
                </a>

            </div>
        </div>
    </div>
    </div>
</body>

</html> --}}
