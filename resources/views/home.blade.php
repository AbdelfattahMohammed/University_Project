<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{url('css/home.css')}}">
    <link rel="stylesheet" href="{{url('css/all_min.css')}}">
    <link rel="stylesheet" href="{{url('css/normalize.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Smart Dashboard</title>
</head>

<body>
    <div class="header">
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
    <div class="content">
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

        <div class="overview">
            <div class="stats-cards">
                <div class="card">
                    <h3>Total Students</h3>
                    <p>{{ $students }}</p>
                </div>
                <div class="card">
                    <h3>Total Teachers</h3>
                    <p>{{ $instructors }}</p>
                </div>
                <div class="card">
                    <h3>Total Courses</h3>
                    <p>{{ $courses }}</p>
                </div>
                <div class="card">
                    <h3>Faculty Rooms</h3>
                    <p>04</p>
                </div>
            </div>
            <div class="attendance">
                <div class="card-header">
                    <h2>Attendance Rate</h2>
                    <span class="attendance-rate">87% <small style="color: #4caf50">+5.25%</small></span>
                </div>
                <p>Average attendance over the last 5 months</p>
                <div class="date-label">May 27</div>
                <div class="chart-container">
                    <svg class="chart" viewBox="0 0 300 160">
                        <polyline points="0,20 50,100 100,70 150,90 200,30 250,10 300,10 350,50 400,10"
                            class="chart-line" />
                    </svg>
                    <div class="chart-labels">
                        <span></span>
                        <span>January</span>
                        <span>February</span>
                        <span>March</span>
                        <span>April</span>
                        <span>May</span>
                        <span>June</span>
                        <span>July</span>
                        <span>August</span>
                        <span>September</span>
                        <span>October</span>
                        <span>November</span>
                        <span>December</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="right-side">
            <div class="calender-container">
                <div class="calendar">
                    <div class="calendar-header">
                        <button onclick="prevMonth()">&#9664;</button>
                        <h3 id="month-year"></h3>
                        <button onclick="nextMonth()">&#9654;</button>
                    </div>
                    <div class="days" id="calendar-days"></div>
                </div>
            </div>
        </div>

    </div>


    <script>
        const monthYear = document.getElementById("month-year");
        const calendarDays = document.getElementById("calendar-days");
        const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        let currentDate = new Date();

        function renderCalendar() {
            calendarDays.innerHTML = "";
            let firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
            let lastDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();

            monthYear.textContent = `${months[currentDate.getMonth()]} ${currentDate.getFullYear()}`;

            ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"].forEach(day => {
                let dayName = document.createElement("div");
                dayName.classList.add("day-name");
                dayName.textContent = day;
                calendarDays.appendChild(dayName);
            });

            for (let i = 0; i < firstDay; i++) {
                let emptyDiv = document.createElement("div");
                calendarDays.appendChild(emptyDiv);
            }

            for (let day = 1; day <= lastDate; day++) {
                let dayElement = document.createElement("div");
                dayElement.classList.add("day");
                dayElement.textContent = day;
                if (
                    day === new Date().getDate() &&
                    currentDate.getMonth() === new Date().getMonth() &&
                    currentDate.getFullYear() === new Date().getFullYear()
                ) {
                    dayElement.classList.add("today");
                }
                calendarDays.appendChild(dayElement);
            }
        }

        function prevMonth() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        }

        function nextMonth() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        }

        renderCalendar();

    </script>


<script>
    function fetchAttendanceData() {
        fetch("/attendanceRate")
            .then(response => response.json())
            .then(data => {
                console.log("Fetched Data:", data); // Debugging

                // Update the attendance rate percentage
                document.querySelector(".attendance-rate").innerHTML = `
                    ${data.overallRate}%
                    <small style="color: ${data.change >= 0 ? '#4caf50' : '#f44336'}">
                        ${data.change >= 0 ? '+' : ''}${data.change}%
                    </small>
                `;

                // Update the latest month label
                document.querySelector(".date-label").textContent = data.monthlyRates[4].month;

                // Update the chart dynamically
                updateChart(data.monthlyRates);
            })
            .catch(error => {
                console.error("Error fetching attendance data:", error);
            });
    }

    function updateChart(monthlyRates) {
        const maxRate = 100; // Maximum attendance percentage
        const svgWidth = 300; // Chart width
        const svgHeight = 150; // Chart height
        const numPoints = monthlyRates.length; // Number of months

        // Calculate x-axis spacing
        const xSpacing = svgWidth / (numPoints - 1);

        // Generate points for the line chart dynamically
        const points = monthlyRates.map((data, index) => {
            const x = index * xSpacing;
            const y = svgHeight - (data.rate / maxRate) * svgHeight; // Scale based on max rate
            return `${x},${y}`;
        }).join(" ");

        // Update polyline points
        document.querySelector(".chart-line").setAttribute("points", points);

        // Update month labels dynamically
        const labels = document.querySelector(".chart-labels");
        labels.innerHTML = ""; // Clear old labels
        monthlyRates.forEach((data) => {
            const span = document.createElement("span");
            span.textContent = data.month;
            labels.appendChild(span);
        });
    }

    // Fetch data on page load
    document.addEventListener("DOMContentLoaded", fetchAttendanceData);
</script>



</body>

</html>
