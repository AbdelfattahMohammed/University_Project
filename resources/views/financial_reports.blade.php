<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Reports</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ url('/css/all.min.css')}}">
    <link rel="stylesheet" href="{{ url('/css/normalize.css')}}">
    <link rel="stylesheet" href="{{ url('/css/financial_reports.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <a href="{{ route('tables') }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>tables</h3>
                </a>
                <a href="{{ route('attendance.index') }}">
                    <i class="fa-solid fa-clipboard-user"></i>
                    <h3>attendance</h3>
                </a>
                <a href="{{ route('financial.financial') }}" class="active">
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
                <h1>Financial Reports </h1>
                <button class="b" onclick="history.back()">Back</button>
            </div>
            <div class="filters">
                <label for="year">Year:</label>
                <select id="year">
                    <option value="2025">2025</option>
                    <option value="2024" selected>2024</option>
                    <option value="2023">2023</option>
                    <option value="2022">2022</option>
                    <option value="2021">2021</option>
                </select>

                <label for="department">Department:</label>
                <select id="department">
                    <option value="All" selected>All</option>
                    <option value="IT">IT</option>
                    <option value="HR">HR</option>
                    <option value="Finance">Finance</option>
                </select>
            </div>

            <div class="report-table">
                <table>
                    <thead>
                        <tr>
                            <th>Report ID</th>
                            <th>Department</th>
                            <th>Year</th>
                            <th>Revenue</th>
                            <th>Expenses</th>
                            <th>Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be inserted dynamically -->
                    </tbody>
                </table>
            </div>

            <div class="charts">
                <canvas id="revenueChart"></canvas>
                <canvas id="expenseChart"></canvas>
            </div>

            <script>
                let revenueChart, expenseChart;

                document.addEventListener("DOMContentLoaded", function () {
                    initializeCharts();
                    fetchFinancialReports();

                    document.getElementById("year").addEventListener("change", fetchFinancialReports);
                    document.getElementById("department").addEventListener("change", fetchFinancialReports);
                });

                function initializeCharts() {
                    const revenueCtx = document.getElementById("revenueChart").getContext("2d");
                    const expenseCtx = document.getElementById("expenseChart").getContext("2d");

                    revenueChart = new Chart(revenueCtx, {
                        type: "bar",
                        data: {
                            labels: [],
                            datasets: [{
                                label: "Revenue",
                                data: [],
                                backgroundColor: "green"
                            }]
                        }
                    });

                    expenseChart = new Chart(expenseCtx, {
                        type: "bar",
                        data: {
                            labels: [],
                            datasets: [{
                                label: "Expenses",
                                data: [],
                                backgroundColor: "red"
                            }]
                        }
                    });
                }

                function fetchFinancialReports() {
                    const year = document.getElementById("year").value;
                    const department = document.getElementById("department").value;

                    fetch(`/financial-reports?year=${year}&department=${department}`)
                        .then(response => response.json())
                        .then(data => {
                            updateTable(data);
                            updateCharts(data);
                        })
                        .catch(error => console.error("Error fetching reports:", error));
                }

                function updateTable(data) {
                    let tableBody = document.querySelector(".report-table tbody");
                    tableBody.innerHTML = "";

                    if (data.length === 0) {
                        tableBody.innerHTML = `<tr><td colspan="6" style="text-align:center;">No reports found</td></tr>`;
                        return;
                    }

                    data.forEach(report => {
                        let row = `<tr>
                            <td>${report.id}</td>
                            <td>${report.department}</td>
                            <td>${report.year}</td>
                            <td>$${parseFloat(report.revenue).toLocaleString()}</td>
                            <td>$${parseFloat(report.expenses).toLocaleString()}</td>
                            <td>$${parseFloat(report.profit).toLocaleString()}</td>
                        </tr>`;
                        tableBody.innerHTML += row;
                    });
                }

                function updateCharts(data) {
                    let labels = data.map(report => report.department);
                    let revenueData = data.map(report => report.revenue);
                    let expenseData = data.map(report => report.expenses);

                    revenueChart.data.labels = labels;
                    revenueChart.data.datasets[0].data = revenueData;
                    revenueChart.update();

                    expenseChart.data.labels = labels;
                    expenseChart.data.datasets[0].data = expenseData;
                    expenseChart.update();
                }
            </script>



</body>

</html>
