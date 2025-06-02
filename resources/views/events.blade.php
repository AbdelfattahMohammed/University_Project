<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ url('/css/all_min.css')}}">
    <link rel="stylesheet" href="{{ url('/css/normalize.css')}}">
    <link rel="stylesheet" href="{{ url('/css/events.css')}}">
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
                <a href="{{ route('financial.financial') }}">
                    <i class="fa-solid fa-receipt"></i>
                    <h3>financial reports</h3>
                </a>
                <a href="{{ route('event.index') }}" class="active">
                    <i class="fa-solid fa-calendar-days"></i>
                    <h3>event</h3>
                </a>
            </div>
        </div>
        <div class="holder">
            <div class="header">
                <h1>Events</h1>
                <button class="b" onclick="history.back()">Back</button>
            </div>
            <div class="filter-container">
                <input type="date" id="dateFilter">
                <select id="typeFilter">
                    <option value="">All</option>
                    <option value="Conference">Conference</option>
                    <option value="Summit">Summit</option>
                    <option value="Workshop">Workshop</option>
                    <option value="Bootcamp">Bootcamp</option>
                </select>
                <input type="text" id="locationFilter" placeholder="Filter by Location">
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody id="eventsTableBody">
                </tbody>
            </table>
            <canvas id="eventChart" width="600" height="400"></canvas>
        </div>
    </div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        fetchEvents();
    });

    function fetchEvents() {
        const date = document.getElementById("dateFilter").value;
        const type = document.getElementById("typeFilter").value;
        const location = document.getElementById("locationFilter").value;

        fetch(`/events?date=${date}&type=${type}&location=${location}`)
            .then(response => response.json())
            .then(data => {
                let tableBody = document.getElementById("eventsTableBody");
                tableBody.innerHTML = ""; // Clear previous data

                let eventCounts = {}; // Object to store event type counts

                data.forEach(event => {
                    let row = `<tr>
                        <td>${event.event_name}</td>
                        <td>${event.event_date}</td>
                        <td>${event.type}</td>
                        <td>${event.location || 'N/A'}</td>
                    </tr>`;
                    tableBody.innerHTML += row;

                    // Count occurrences of each event type
                    eventCounts[event.type] = (eventCounts[event.type] || 0) + 1;
                });

                // Generate Chart after data is populated
                generateChart(eventCounts);
            })
            .catch(error => console.error("Error fetching events:", error));
    }

    function generateChart(eventCounts) {
        const ctx = document.getElementById("eventChart").getContext("2d");

        // Destroy existing chart before creating a new one
        if (window.myChart) {
            window.myChart.destroy();
        }

        // Create the new chart
        window.myChart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: Object.keys(eventCounts), // Event types
                datasets: [{
                    label: "Number of Events",
                    data: Object.values(eventCounts), // Event counts
                    backgroundColor: "rgba(54, 162, 235, 0.5)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Event Listeners for filters
    document.getElementById("dateFilter").addEventListener("change", fetchEvents);
    document.getElementById("typeFilter").addEventListener("change", fetchEvents);
    document.getElementById("locationFilter").addEventListener("keyup", fetchEvents);
</script>



</body>

</html>
