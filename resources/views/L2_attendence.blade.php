<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ url('/css/all_min.css')}}">
    <link rel="stylesheet" href="{{ url('/css/normalize.css')}}">
    <link rel="stylesheet" href="{{ url('/css/L2_attendence.css')}}">
    <title>L1-Attendence Page</title>

</head>

<body>
    <div class="main-header">
        <div class="logo"><img src="{{ asset('images/smart dashboard Logo 4.png') }}" alt="">
            <div class="text">
                <p style="margin-top: 10px;" ><span>smart</span>
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
                    <h1>Attendance</h1>
                    <button class="btn btn-secondary" onclick="history.back()">Back</button>
                </div>

                <div class="mb-3">
                    <input type="date" id="attendanceDate" class="form-control">
                </div>

                <canvas id="attendanceChart" width="200" height="50"></canvas>

                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceTable">
                        <tr>
                            <td colspan="3" class="text-center">Loading attendance records...</td>
                        </tr>
                    </tbody>
                </table>
            </div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let attendanceChart;

    function loadAttendance(selectedDate = null) {
        const tableBody = document.getElementById("attendanceTable");

        let url = `/attendanceLev2`; // Fetch Year 2 students
        if (selectedDate) {
            url += `?date=${selectedDate}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = "";

                let presentCount = 0;
                let absentCount = 0;

                if (data.length > 0) {
                    data.forEach(record => {
                        const status = record.status ? record.status : 'Absent';
                        if (status === 'Present') {
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
                    tableBody.innerHTML = `<tr><td colspan="3" class="text-center">No attendance records found</td></tr>`;
                }

                updateChart(presentCount, absentCount);
            })
            .catch(error => {
                console.error("Error fetching attendance data:", error);
                tableBody.innerHTML = `<tr><td colspan="3" class="text-center text-danger">Error fetching data</td></tr>`;
            });
    }

    function updateChart(present, absent) {
        const ctx = document.getElementById("attendanceChart").getContext("2d");

        if (attendanceChart) {
            attendanceChart.destroy(); // Destroy previous chart before re-rendering
        }

        attendanceChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Present", "Absent"],
                datasets: [{
                    label: "Attendance Count",
                    data: [present, absent],
                    backgroundColor: ["#28a745", "#dc3545"],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Load attendance for all Year 2 students on page load
    document.addEventListener("DOMContentLoaded", () => {
        loadAttendance();
    });

    // Filter when date is selected
    document.getElementById("attendanceDate").addEventListener("change", function () {
        loadAttendance(this.value);
    });
</script>







        </div>
    </div>
</body>




</html>
