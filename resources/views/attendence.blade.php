<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ url('/css/all_min.css') }}">
    <link rel="stylesheet" href="{{ url('/css/normalize.css') }}">
    <link rel="stylesheet" href="{{ url('/css/L1_attendence.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Attendance Page</title> {{-- Changed title to be more generic --}}

</head>

<body>
    <div class="main-header">
        <div class="logo"><img src="{{ asset('images/smart dashboard Logo 4.png') }}" alt="">
            <div class="text">
                <p style="margin-top: 10px; "><span>smart</span>
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

                <a href="{{ route('tables') }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>tables</h3>
                </a>

                <a href="{{ route('attendance.index') }}" class="active">
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

            <div class="container mt-4">
                <div class="header d-flex justify-content-between align-items-center mb-3">
                    <h1>Student Attendance</h1> {{-- Changed title --}}
                    <button class="btn btn-secondary" onclick="history.back()">Back</button>
                </div>

                {{-- Added Select Year dropdown --}}
                <div class="filter-section d-flex align-items-center mb-3 gap-3">
                    <label for="selectYear" class="form-label mb-0">Select Year:</label>
                    <select class="form-select w-auto" id="selectYear">
                        <option value="1">Year 1</option>
                        <option value="2">Year 2</option>
                        <option value="3">Year 3</option>
                        <option value="4">Year 4</option>
                    </select>

                    <label for="attendanceDate" class="form-label mb-0">Select Date:</label>
                    <input type="date" id="attendanceDate" class="form-control w-auto">

                    <label for="studentNameSearch" class="form-label mb-0">Search Student:</label>
                    <input type="text" id="studentNameSearch" class="form-control w-auto"
                        placeholder="Enter student name">
                    <button class="btn btn-primary" id="searchStudentButton">Search</button>
                </div>

                <div class="row">
                    <div class="col-md-7"> {{-- Adjusted column width for table --}}
                        <h2 class="mb-3">Attendance Records</h2>
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="attendanceTable">
                                {{-- Attendance data will be loaded here by JavaScript --}}
                                <tr>
                                    <td colspan="3" class="text-center">Loading attendance records...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-5"> {{-- Adjusted column width for chart --}}
                        <h2 class="mb-3">Attendance Summary</h2>
                        <div class="chart-container">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                let attendanceChart;
                let currentYear = 1; // Default to Year 1 on page load

                function loadAttendance(year, selectedDate = null, studentName = null) {
                const tableBody = document.getElementById("attendanceTable");
                // Show loading message
                tableBody.innerHTML = `<tr><td colspan="3" class="text-center">Loading attendance records...</td></tr>`;

                let url = `/attendance-by-year/${year}`;
                const params = new URLSearchParams();

                if (selectedDate) {
                    params.append('date', selectedDate);
                }
                // إضافة اسم الطالب للباراميترات إذا تم توفيره
                if (studentName) {
                    params.append('student_name', studentName);
                }

                if (params.toString()) {
                    url += `?${params.toString()}`;
                }

                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        tableBody.innerHTML = ""; // Clear loading message

                        let presentCount = 0;
                        let absentCount = 0;

                        if (data.length > 0) {
                            data.forEach(record => {
                                const status = record.status ? record.status : 'Absent';
                                if (status.toLowerCase() === 'present') {
                                    presentCount++;
                                } else {
                                    absentCount++;
                                }

                                const row = `<tr>
                                        <td>${record.name}</td>
                                        <td>${record.attendance_date ? record.attendance_date : 'N/A'}</td>
                                        <td>${status}</td>
                                    </tr>`;
                                tableBody.innerHTML += row;
                            });
                        } else {
                            tableBody.innerHTML = `<tr><td colspan="3" class="text-center">No attendance records found for this year, date, or student.</td></tr>`;
                        }

                        updateChart(presentCount, absentCount);
                    })
                    .catch(error => {
                        console.error("Error fetching attendance data:", error);
                        tableBody.innerHTML = `<tr><td colspan="3" class="text-center text-danger">Error fetching data: ${error.message}</td></tr>`;
                    });
            }

            function updateChart(present, absent) {
                const ctx = document.getElementById("attendanceChart").getContext("2d");

                if (attendanceChart) {
                    attendanceChart.destroy(); // Destroy previous instance before updating
                }

                attendanceChart = new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: ["Present", "Absent"],
                        datasets: [{
                            label: "Attendance",
                            data: [present, absent],
                            backgroundColor: ["#28a745", "#dc3545"], // Green for present, Red for absent
                            borderColor: ["#218838", "#c82333"],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        if (Number.isInteger(value)) {
                                            return value;
                                        }
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true
                            }
                        }
                    }
                });
            }

            // Event listener for year selection
            document.getElementById("selectYear").addEventListener("change", function () {
                currentYear = this.value;
                const selectedDate = document.getElementById("attendanceDate").value;
                const studentName = document.getElementById("studentNameSearch").value.trim(); // جلب اسم الطالب
                loadAttendance(currentYear, selectedDate, studentName);
            });

            // Event listener for date selection
            document.getElementById("attendanceDate").addEventListener("change", function () {
                const selectedDate = this.value;
                const studentName = document.getElementById("studentNameSearch").value.trim(); // جلب اسم الطالب
                loadAttendance(currentYear, selectedDate, studentName);
            });

            // Event listener for new student search button
            document.getElementById("searchStudentButton").addEventListener("click", function () {
                const selectedDate = document.getElementById("attendanceDate").value;
                const studentName = document.getElementById("studentNameSearch").value.trim(); // جلب اسم الطالب
                loadAttendance(currentYear, selectedDate, studentName);
            });

            // Initial load on page load for the default selected year and today's date
            document.addEventListener("DOMContentLoaded", () => {
                // Set default selected year in the dropdown
                document.getElementById('selectYear').value = currentYear;

                // Set default date to today
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                const formattedToday = `${year}-${month}-${day}`;

                const attendanceDateInput = document.getElementById('attendanceDate');
                attendanceDateInput.value = formattedToday;

                // Load attendance data for the default year and today's date (no student name initially)
                loadAttendance(currentYear, formattedToday, null);
            });
            </script>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
