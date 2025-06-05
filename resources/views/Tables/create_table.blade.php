<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ url('/css/all_min.css')}}">
    <link rel="stylesheet" href="{{ url('/css/normalize.css')}}">
    <link rel="stylesheet" href="{{ url('/css/create_table.css')}}">
    <title>create Timetable</title>

</head>

<body>
    <div class="main-header">
        <div class="logo"><img src="{{ asset('images/smart dashboard Logo 4.png') }}" alt="">
            <div class="text">
                <p ><span>smart</span>
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
                <a href="{{ route('student.index') }}" >
                    <i class="fas fa-user-graduate"></i>
                    <h3>Students</h3>
                </a>
                <a href="{{ route('instructor') }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>instructor</h3>
                </a>
                
                <a href="{{route('tables')}}" class="active">
                <i class="fa-solid fa-table"></i>
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
                <h1>create timetable</h1>
                <button class="b" onclick="history.back()">Back</button>
            </div>
            <div class="container">
        
        <div class="lev-select">
            <label for="levelSelect">Select Level:</label>
        <select id="levelSelect">
            <option value="">-- Select Level --</option>
        </select>
        </div>
        

        <div class="lev-select">
        <label for="sectionSelect">Select Section:</label>
        <select id="sectionSelect" disabled>
            <option value="">-- Select Section --</option>
        </select>
        </div>
        

        <div id="timetableContainer" style="margin-top:20px;"></div>

        
        <button class="btn" id="saveBtn" disabled>Save Timetable</button>
        <button class="btn" id="printBtn">Print Timetable</button>
    </div>

    
        </div>
    </div>
    <script>
        const data = {
            levels: {
                2: { sections: ['CS', 'IS', 'AI', 'BIO'] },
                3: { sections: ['CS', 'IS', 'AI', 'BIO'] },
                4: { sections: ['CS', 'IS', 'AI', 'BIO'] },
                1: { sections: ['CS', 'IS', 'AI', 'BIO'] },
            },
            subjects: {
                '1_CS': ['Programming 101', 'Data Structures', 'Algorithms'],
                '2_CS': ['Operating Systems', 'Databases', 'Networks'],
                '3_CS': ['AI', 'Machine Learning', 'Computer Vision'],
                '4_CS': ['Advanced AI', 'Distributed Systems'],
                '1_IS': ['Calculus I', 'Algebra I', 'Statistics'],
                '2_IS': ['Calculus II', 'Linear Algebra', 'Probability'],
                '3_IS': ['Abstract Algebra', 'Numerical Methods'],
                '4_IS': ['Topology', 'Real Analysis'],
            },
            doctors: {
                'Programming 101': ['Dr. Ahmed', 'Dr. Mona'],
                'Data Structures': ['Dr. Samir'],
                'Algorithms': ['Dr. Youssef', 'Dr. Laila'],
                'Operating Systems': ['Dr. Hossam'],
                'Databases': ['Dr. Noura'],
                'Networks': ['Dr. Kareem'],
                'Calculus I': ['Dr. Salma'],
                'Algebra I': ['Dr. Tarek']
            },
            rooms: {
                'Dr. Ahmed': ['Room A1', 'Room A2'],
                'Dr. Mona': ['Room B1'],
                'Dr. Samir': ['Room C1', 'Room C2'],
                'Dr. Youssef': ['Room D1'],
                'Dr. Laila': ['Room E1'],
                'Dr. Hossam': ['Room F1'],
                'Dr. Noura': ['Room G1'],
                'Dr. Kareem': ['Room H1'],
                'Dr. Salma': ['Room I1'],
                'Dr. Tarek': ['Room J1']
            }
        };

        const days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
        const periods = ['9:00 - 11:00', '11:00 - 1:00', '1:00 - 3:00', '3:00 - 5:00', '5:00 - 7:00'];

        const levelSelect = document.getElementById('levelSelect');
        const sectionSelect = document.getElementById('sectionSelect');
        const timetableContainer = document.getElementById('timetableContainer');
        const saveBtn = document.getElementById('saveBtn');
        const printBtn = document.getElementById('printBtn');

        function fillLevels() {
            for (const level in data.levels) {
                const option = document.createElement('option');
                option.value = level;
                option.textContent = `Level ${level}`;
                levelSelect.appendChild(option);
            }
        }

        function fillSections(level) {
            sectionSelect.innerHTML = '<option value="">-- Select Section --</option>';
            if (!level) {
                sectionSelect.disabled = true;
                return;
            }
            data.levels[level].sections.forEach(section => {
                const option = document.createElement('option');
                option.value = section;
                option.textContent = section;
                sectionSelect.appendChild(option);
            });
            sectionSelect.disabled = false;
        }

        function createEmptyTimetable() {
            timetableContainer.innerHTML = '';
            const table = document.createElement('table');
            const thead = document.createElement('thead');
            const headerRow = document.createElement('tr');
            const dayHeader = document.createElement('th');
                dayHeader.textContent = 'Days';
                dayHeader.classList.add('days-header');
                headerRow.appendChild(dayHeader);

            periods.forEach(period => {
                const th = document.createElement('th');
                th.textContent = period;
                headerRow.appendChild(th);
            });
            thead.appendChild(headerRow);
            table.appendChild(thead);

            const tbody = document.createElement('tbody');
            days.forEach(day => {
                const tr = document.createElement('tr');
                const dayCell = document.createElement('td');
                dayCell.textContent = day;
                tr.appendChild(dayCell);

                periods.forEach(() => {
                    const td = document.createElement('td');
                    td.textContent = '-'; // empty cell placeholder
                    tr.appendChild(td);
                });

                tbody.appendChild(tr);
            });

            table.appendChild(tbody);
            timetableContainer.appendChild(table);

            saveBtn.disabled = true; // disable save since no data
        }

        function createTimetable(level, section) {
            timetableContainer.innerHTML = '';
            if (!level || !section) {
                createEmptyTimetable();
                return;
            }

            saveBtn.disabled = false;

            const table = document.createElement('table');

            const thead = document.createElement('thead');
            const headerRow = document.createElement('tr');
            headerRow.appendChild(document.createElement('th'));
            periods.forEach(period => {
                const th = document.createElement('th');
                th.textContent = period;
                headerRow.appendChild(th);
            });
            thead.appendChild(headerRow);
            table.appendChild(thead);

            const tbody = document.createElement('tbody');
            days.forEach(day => {
                const tr = document.createElement('tr');
                const dayCell = document.createElement('td');
                dayCell.textContent = day;
                tr.appendChild(dayCell);

                periods.forEach(() => {
                    const td = document.createElement('td');

                    // Create selects
                    const subjectSelect = document.createElement('select');
                    subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                    const subjectKey = `${level}_${section}`;
                    const subjectsList = data.subjects[subjectKey] || [];
                    subjectsList.forEach(sub => {
                        const opt = document.createElement('option');
                        opt.value = sub;
                        opt.textContent = sub;
                        subjectSelect.appendChild(opt);
                    });

                    const doctorSelect = document.createElement('select');
                    doctorSelect.innerHTML = '<option value="">Select Doctor</option>';
                    doctorSelect.disabled = true;

                    const roomSelect = document.createElement('select');
                    roomSelect.innerHTML = '<option value="">Select Room</option>';
                    roomSelect.disabled = true;

                    subjectSelect.addEventListener('change', () => {
                        const selectedSubject = subjectSelect.value;
                        doctorSelect.innerHTML = '<option value="">Select Doctor</option>';
                        roomSelect.innerHTML = '<option value="">Select Room</option>';
                        roomSelect.disabled = true;
                        if (selectedSubject && data.doctors[selectedSubject]) {
                            data.doctors[selectedSubject].forEach(doc => {
                                const opt = document.createElement('option');
                                opt.value = doc;
                                opt.textContent = doc;
                                doctorSelect.appendChild(opt);
                            });
                            doctorSelect.disabled = false;
                        } else {
                            doctorSelect.disabled = true;
                        }
                    });

                    doctorSelect.addEventListener('change', () => {
                        const selectedDoctor = doctorSelect.value;
                        roomSelect.innerHTML = '<option value="">Select Room</option>';
                        if (selectedDoctor && data.rooms[selectedDoctor]) {
                            data.rooms[selectedDoctor].forEach(room => {
                                const opt = document.createElement('option');
                                opt.value = room;
                                opt.textContent = room;
                                roomSelect.appendChild(opt);
                            });
                            roomSelect.disabled = false;
                        } else {
                            roomSelect.disabled = true;
                        }
                    });

                    td.appendChild(subjectSelect);
                    td.appendChild(doctorSelect);
                    td.appendChild(roomSelect);

                    tr.appendChild(td);
                });

                tbody.appendChild(tr);
            });

            table.appendChild(tbody);
            timetableContainer.appendChild(table);
        }

        function saveTimetable() {
            const timetable = [];
            const table = timetableContainer.querySelector('table');
            if (!table) return alert('No timetable to save!');
            const rows = table.tBodies[0].rows;
            for (let r = 0; r < rows.length; r++) {
                const day = rows[r].cells[0].textContent;
                for (let c = 1; c < rows[r].cells.length; c++) {
                    const cell = rows[r].cells[c];
                    const selects = cell.querySelectorAll('select');
                    const subject = selects[0] ? selects[0].value : null;
                    const doctor = selects[1] ? selects[1].value : null;
                    const room = selects[2] ? selects[2].value : null;
                    timetable.push({ day, period: periods[c - 1], subject, doctor, room });
                }
            }
            console.log('Saved Timetable:', timetable);
            alert('Timetable data saved! (Check console)');
        }

        function printTimetable() {
            const table = timetableContainer.querySelector('table');
            if (!table) return alert('No timetable to print!');
            const printWindow = window.open('', '', 'width=900,height=600');
            printWindow.document.write('<html><head><title>Print Timetable</title>');
            printWindow.document.write('<style>table{border-collapse:collapse;width:100%;}th,td{border:1px solid #444;padding:8px;text-align:center;}th{background:#eee;}</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(table.outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }

        // Initial fill
        fillLevels();
        createEmptyTimetable();

        // Event listeners
        levelSelect.addEventListener('change', () => {
            const level = levelSelect.value;
            fillSections(level);
            sectionSelect.value = '';
            createEmptyTimetable();
        });

        sectionSelect.addEventListener('change', () => {
            const level = levelSelect.value;
            const section = sectionSelect.value;
            createTimetable(level, section);
        });

        saveBtn.addEventListener('click', saveTimetable);
        printBtn.addEventListener('click', printTimetable);

    </script>
</body>
</html>
