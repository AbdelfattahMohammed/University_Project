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
                <a href="{{ route('overview') }}" class="active">
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
                    <p>{{ $rooms }}</p>
                </div>
            </div>
            <div class="attendance">
                <div class="card-header">
                    <h2>Attendance Rate</h2>
                    {{-- These values will be updated by JS --}}
                    <span class="attendance-rate">Loading...</span>
                </div>
                <p>Average attendance over the last 5 months</p>
                {{-- This label will be updated by JS --}}
                <div class="date-label"></div>
                <div class="chart-container">
                    <svg class="chart" viewBox="0 0 300 160">
                        {{-- Polyline points will be set by JS --}}
                        <polyline points="" class="chart-line" />
                    </svg>
                    <div class="chart-labels">
                        {{-- Labels will be set by JS --}}
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
            <div class="logout">
                    {{-- هنا هنضيف زرار تسجيل الخروج --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                            <!-- <i class="fa-solid fa-right-from-bracket"></i> {{-- أيقونة لتسجيل الخروج --}} -->
                            <h3>log out</h3>
                        </a>
                    </form>
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
            fetch("/attendance-rate")
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Fetched Data:", data); // Debugging

                    // Update the attendance rate percentage
                    document.querySelector(".attendance-rate").innerHTML = `
                        ${data.overallRate}%
                        <small style="color: ${data.change >= 0 ? '#4caf50' : '#f44336'}">
                            ${data.change >= 0 ? '+' : ''}${data.change}%
                        </small>
                    `;

                    // Update the latest month label - assuming last element is the most recent
                    if (data.monthlyRates && data.monthlyRates.length > 0) {
                        document.querySelector(".date-label").textContent = data.monthlyRates[data.monthlyRates.length - 1].month;
                    }


                    // Update the chart dynamically
                    updateChart(data.monthlyRates);
                })
                .catch(error => {
                    console.error("Error fetching attendance data:", error);
                    document.querySelector(".attendance-rate").innerHTML = `<span style="color:red;">Error loading data</span>`;
                    document.querySelector(".date-label").textContent = `N/A`;
                    // You might want to clear or show an error state for the SVG chart as well
                });
        }

        function updateChart(monthlyRates) {
            const svgElement = document.querySelector(".chart");
            const polylineElement = document.querySelector(".chart-line");
            const labelsContainer = document.querySelector(".chart-labels");

            if (!monthlyRates || monthlyRates.length === 0) {
                // Clear the chart if no data
                polylineElement.setAttribute("points", "");
                labelsContainer.innerHTML = "";
                return;
            }

            const viewBox = svgElement.getAttribute('viewBox').split(' ').map(Number);
            const svgWidth = viewBox[2]; // width
            const svgHeight = viewBox[3]; // height

            const maxRate = 100; // Attendance percentage is out of 100
            const numPoints = monthlyRates.length;

            // Calculate x-axis spacing based on the number of points
            // Divide by (numPoints - 1) to ensure the last point hits the end of the SVG
            const xSpacing = numPoints > 1 ? svgWidth / (numPoints - 1) : 0;

            // Generate points for the line chart dynamically
            const points = monthlyRates.map((data, index) => {
                const x = index * xSpacing;
                // Scale Y-axis: 0% at bottom (svgHeight), 100% at top (0)
                const y = svgHeight - (data.rate / maxRate) * svgHeight;
                return `${x},${y}`;
            }).join(" ");

            // Update polyline points
            polylineElement.setAttribute("points", points);

            // Update month labels dynamically
            labelsContainer.innerHTML = ""; // Clear old labels
            monthlyRates.forEach((data, index) => {
                const span = document.createElement("span");
                span.textContent = data.month;
                // Optional: set position for labels if needed for more precise alignment
                // For example, if you want them centered under each point:
                // const xPos = index * xSpacing;
                // span.style.position = 'absolute';
                // span.style.left = `${xPos}px`;
                // span.style.transform = 'translateX(-50%)'; // Center the label
                labelsContainer.appendChild(span);
            });
        }

        // Fetch data on page load
        document.addEventListener("DOMContentLoaded", fetchAttendanceData);
    </script>



</body>

</html>
