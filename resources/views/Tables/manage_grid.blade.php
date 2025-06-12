<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة جدول قسم {{ $selectedDepartment->department_name }} - سنة {{ $year }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <style>
        /* الأنماط الأساسية لضمان ظهور الصفحة بشكل مقبول */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            /* Ensure body takes full viewport height */
        }

        .main-content {
            display: flex;
            flex-grow: 1;
        }

        .holder {
            flex-grow: 1;
            padding: 20px;
            background-color: #fff;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2.2rem;
            color: #333;
            margin: 0;
        }

        .b {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .b:hover {
            background-color: #0056b3;
        }

        .table-container {
            overflow-x: auto;
            /* Enable horizontal scrolling for smaller screens */
            width: 100%;
        }

        .timetable-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            min-width: 800px;
            /* Ensure a minimum width for the table */
        }

        .timetable-grid th,
        .timetable-grid td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
            vertical-align: middle;
            font-size: 0.9rem;
            min-width: 120px;
            /* لضمان عرض كافٍ للخلايا */
        }

        .timetable-grid thead th {
            background-color: #e2e8f0;
            color: #333;
            font-weight: 700;
        }

        .timetable-grid tbody th.day-name {
            background-color: #f0f4f7;
            font-weight: 600;
            color: #555;
        }

        .timetable-slot {
            cursor: pointer;
            background-color: #e6f7ff;
            /* لون للخلايا القابلة للتعديل */
            transition: background-color 0.2s ease;
        }

        .timetable-slot:hover {
            background-color: #cceeff;
        }

        .empty-slot {
            background-color: #f9f9f9;
            color: #aaa;
            font-style: italic;
        }

        /* أنماط الـ Modal (نافذة التعديل المنبثقة) */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            /* display: none; */
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 600px;
            position: relative;
        }

        .close-button {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 20px;
            cursor: pointer;
        }

        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
        }

        .modal-body label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        .modal-body select,
        .modal-body input[type="hidden"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            /* لضمان أن العرض يشمل الـ padding والـ border */
        }

        .modal-footer {
            text-align: right;
            margin-top: 20px;
        }

        .modal-footer button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            margin-left: 10px;
        }

        .modal-footer .save-button {
            background-color: #28a745;
            color: white;
        }

        .modal-footer .save-button:hover {
            background-color: #218838;
        }

        .modal-footer .clear-button {
            background-color: #dc3545;
            color: white;
        }

        .modal-footer .clear-button:hover {
            background-color: #c82333;
        }

        .modal-footer .cancel-button {
            background-color: #6c757d;
            color: white;
        }

        .modal-footer .cancel-button:hover {
            background-color: #5a6268;
        }

        /* **[إضافة: CSS بسيط لتصميم الرسالة]** */
        .message-box {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            position: fixed;
            /* لتظهر فوق المحتوى */
            top: 20px;
            /* المسافة من الأعلى */
            left: 50%;
            transform: translateX(-50%);
            /* لتوسيط الرسالة أفقياً */
            z-index: 1000;
            /* للتأكد من أنها تظهر فوق العناصر الأخرى */
            width: fit-content;
            min-width: 300px;
            /* لضمان عرض كافٍ للرسالة */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: opacity 0.5s ease-in-out, visibility 0.5s ease-in-out;
            /* لإضافة تأثير اختفاء تدريجي */
            opacity: 0;
            /* مخفية في البداية */
            visibility: hidden;
            /* Start hidden */
        }

        .message-box.show {
            opacity: 1;
            visibility: visible;
        }

        .message-box.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message-box.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <div id="successMessage" class="message-box success">
    </div>
    <div id="errorMessage" class="message-box error">
    </div>

    <div class="main-content">

        <div class="holder">
            <div class="header">
                <h1>إدارة جدول قسم: {{ $selectedDepartment->department_name }} (سنة {{ $year }})</h1>
                <button class="b" onclick="history.back()">عودة</button>
            </div>

            <div class="table-container">
                <table class="timetable-grid">
                    <thead>
                        <tr>
                            <th class="days">الأيام / الفترات</th>
                            @foreach ($periods as $periodNum => $periodTime)
                                <th>{{ $periodTime }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($days as $day)
                            <tr>
                                <th class="day-name">{{ $day }}</th>
                                @foreach ($periods as $periodNum => $periodTime)
                                    @php
                                        $slot = $timetableData[$day][$periodNum] ?? null;
                                        $slotId = $slot ? $slot->id : '';
                                        $courseId = $slot && $slot->course ? $slot->course->id : '';
                                        $professorId = $slot && $slot->professor ? $slot->professor->id : '';
                                        $assistantId = $slot && $slot->assistant ? $slot->assistant->id : '';
                                        $roomId = $slot && $slot->room ? $slot->room->id : '';
                                    @endphp
                                    <td class="timetable-slot @if (!$slot) empty-slot @endif"
                                        data-day="{{ $day }}" data-period="{{ $periodNum }}"
                                        data-id="{{ $slotId }}" data-course-id="{{ $courseId }}"
                                        data-professor-id="{{ $professorId }}"
                                        data-assistant-id="{{ $assistantId }}" data-room-id="{{ $roomId }}">
                                        @if ($slot)
                                            <p><span>{{ $slot->course->course_name ?? 'غير محدد' }}</span></p>
                                            @if ($slot->professor)
                                                <p>د. {{ $slot->professor->full_name }}</p>
                                            @endif
                                            @if ($slot->assistant)
                                                <p>م. {{ $slot->assistant->full_name }}</p>
                                            @endif
                                            @if ($slot->room)
                                                <p>قاعة: {{ $slot->room->room_name }}</p>
                                            @endif
                                        @else
                                            اضغط للإضافة
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="timetableModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="closeModal()">&times;</span>
            <h2>إدارة حصة الجدول</h2>
            <form id="timetableForm">
                <input type="hidden" id="slotId" name="id">
                <input type="hidden" id="modalDay" name="day">
                <input type="hidden" id="modalPeriod" name="period_number">
                <input type="hidden" id="modalDepartmentId" name="department_id"
                    value="{{ $selectedDepartment->id }}">
                <input type="hidden" id="modalYear" name="year" value="{{ $year }}">

                <div class="modal-body">
                    <label for="courseSelect">المقرر:</label>
                    <select id="courseSelect" name="course_id">
                        <option value="">اختر مقرر (اختياري)</option>
                        @foreach ($availableCourses as $course)
                            <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                        @endforeach
                    </select>

                    <label for="professorSelect">الأستاذ:</label>
                    <select id="professorSelect" name="professor_id">
                        <option value="">اختر أستاذ (اختياري)</option>
                        {{-- Professors will be loaded here via JS --}}
                    </select>

                    <label for="assistantSelect">المعيد:</label>
                    <select id="assistantSelect" name="assistant_id">
                        <option value="">اختر معيد (اختياري)</option>
                        {{-- Assistants will be loaded here via JS --}}
                    </select>

                    <label for="roomSelect">القاعة:</label>
                    <select id="roomSelect" name="room_id">
                        <option value="">اختر قاعة (اختياري)</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->room_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="save-button" onclick="saveTimetableSlot()">حفظ</button>
                    <button type="button" class="clear-button" onclick="clearTimetableSlot()">مسح الحصة</button>
                    <button type="button" class="cancel-button" onclick="closeModal()">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // **[تعديل: تعريف المتغيرات JavaScript بالبيانات من PHP]**
        const allProfessors = @json($professors);
        const allAssistants = @json($assistants);
        const allRooms = @json($rooms); // Rooms are already populated directly in the HTML

        const timetableModal = document.getElementById('timetableModal');
        const timetableForm = document.getElementById('timetableForm');
        let currentSelectedCell = null; // للاحتفاظ بالخلية التي تم النقر عليها

        // **[إضافة: عناصر الرسائل]**
        const successMessageDiv = document.getElementById('successMessage');
        const errorMessageDiv = document.getElementById('errorMessage');
        const courseSelect = document.getElementById('courseSelect');
        const professorSelect = document.getElementById('professorSelect');
        const assistantSelect = document.getElementById('assistantSelect');
        const roomSelect = document.getElementById('roomSelect');


        // **[إضافة: دالة عرض الرسائل]**
        function showMessage(message, type = 'success', duration = 5000) {
            let messageDiv;
            if (type === 'success') {
                messageDiv = successMessageDiv;
                errorMessageDiv.classList.remove('show');
            } else {
                messageDiv = errorMessageDiv;
                successMessageDiv.classList.remove('show');
            }

            messageDiv.textContent = message;
            messageDiv.classList.add('show');

            setTimeout(() => {
                messageDiv.classList.remove('show');
            }, duration);
        }

        // دالة لفتح الـ Modal
        function openModal(cell) {
            currentSelectedCell = cell;

            const slotId = cell.dataset.id;
            const day = cell.dataset.day;
            const period = cell.dataset.period;
            const courseId = cell.dataset.courseId; // This might be an empty string or 'null'
            const professorId = cell.dataset.professorId;
            const assistantId = cell.dataset.assistantId;
            const roomId = cell.dataset.roomId; // Get the room ID

            document.getElementById('slotId').value = slotId;
            document.getElementById('modalDay').value = day;
            document.getElementById('modalPeriod').value = period;
            document.getElementById('modalDepartmentId').value = "{{ $selectedDepartment->id }}";
            document.getElementById('modalYear').value = "{{ $year }}";

            // Call filterInstructorsForCourse first, then set selected values
            // Ensure courseId is passed as null or an empty string if it's not set
            filterInstructorsForCourse(courseId === '' ? null : courseId).then(() => {
                // Set the selected values *after* the options are populated
                courseSelect.value = courseId; // Set the actual value here, which might be ''
                professorSelect.value = professorId;
                assistantSelect.value = assistantId;
            });
            roomSelect.value = roomId; // Set the room ID here

            timetableModal.style.display = 'flex';
        }

        // دالة لإغلاق الـ Modal
        function closeModal() {
            timetableModal.style.display = 'none'; // إخفاء الـ modal
            timetableForm.reset(); // مسح بيانات الفورم
            currentSelectedCell = null;
            // Clear instructor dropdowns completely before re-populating on next open
            professorSelect.innerHTML = '<option value="">اختر أستاذ (اختياري)</option>';
            assistantSelect.innerHTML = '<option value="">اختر معيد (اختياري)</option>';
            // Re-populate rooms if they were cleared (only if they were cleared, which they are not here)
            // No need to re-populate rooms here, as they are static.
            // roomSelect.innerHTML = '<option value="">اختر قاعة (اختياري)</option>';
            // allRooms.forEach(room => {
            //     roomSelect.innerHTML += `<option value="${room.id}">${room.room_name}</option>`;
            // });
        }

        // إضافة حدث النقر على كل خلية في الجدول لفتح الـ Modal
        document.querySelectorAll('.timetable-slot').forEach(cell => {
            cell.addEventListener('click', () => openModal(cell));
        });

        // Event listener for course selection change
        courseSelect.addEventListener('change', () => filterInstructorsForCourse());


        // دالة لفلترة الأساتذة والمعيدين بناءً على المقرر المختار
        async function filterInstructorsForCourse(selectedCourseId = null) {
            const courseId = selectedCourseId || courseSelect.value;

            professorSelect.innerHTML = '<option value="">اختر أستاذ (اختياري)</option>';
            assistantSelect.innerHTML = '<option value="">اختر معيد (اختياري)</option>';

            if (!courseId || courseId === 'null') { // Add explicit check for 'null' string if it comes from dataset
                allProfessors.forEach(prof => {
                    professorSelect.innerHTML += `<option value="${prof.id}">${prof.full_name}</option>`;
                });
                allAssistants.forEach(asst => {
                    assistantSelect.innerHTML += `<option value="${asst.id}">${asst.full_name}</option>`;
                });
                return;
            }

            try {
                const response = await fetch(`/api/timetables/course-instructors/${encodeURIComponent(courseId)}`);
                if (!response.ok) {
                    // Read error message from backend if available
                    let errorData;
                    try {
                        errorData = await response.json();
                    } catch (e) {
                        const errorText = await response.text();
                        console.error('Non-JSON error response for instructors:', errorText);
                        throw new Error('Failed to fetch instructors (non-JSON response).');
                    }
                    throw new Error(errorData.error || `HTTP error! status: ${response.status}`);
                }
                const instructors = await response.json();

                instructors.forEach(instructor => {
                    if (instructor.position && instructor.position.trim().toLowerCase() === 'professor') {
                        professorSelect.innerHTML +=
                            `<option value="${instructor.id}">${instructor.full_name}</option>`;
                    } else if (instructor.position && instructor.position.trim().toLowerCase() ===
                        'assistant') {
                        assistantSelect.innerHTML +=
                            `<option value="${instructor.id}">${instructor.full_name}</option>`;
                    }
                });

            } catch (error) {
                console.error('Error fetching instructors:', error);
                showMessage('حدث خطأ أثناء جلب بيانات الأساتذة والمعيدين للمقرر.', 'error');
                // Fallback: Populate with all instructors if API fails
                allProfessors.forEach(prof => {
                    professorSelect.innerHTML += `<option value="${prof.id}">${prof.full_name}</option>`;
                });
                allAssistants.forEach(asst => {
                    assistantSelect.innerHTML += `<option value="${asst.id}">${asst.full_name}</option>`;
                });
            }
        }


        // دالة لحفظ أو تحديث خانة الجدول
        async function saveTimetableSlot() {
            const formData = new FormData(timetableForm);
            const data = Object.fromEntries(formData.entries());

            for (const key in data) {
                if (data[key] === '') {
                    data[key] = null; // Convert empty strings to null
                }
            }

            try {
                const response = await fetch("{{ route('api.timetables.save_slot') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                let responseData;
                const responseText = await response.text(); // حاول تقرا الـ response كـ text أولاً
                console.log('Backend Response (Text):', responseText); // Log الـ response الخام

                try {
                    responseData = JSON.parse(responseText); // حاول تحول الـ text لـ JSON
                } catch (jsonParseError) {
                    console.error('Failed to parse JSON response:', jsonParseError);
                    console.error('Non-JSON response:', responseText);
                    showMessage('حدث خطأ غير متوقع من الخادم. يرجى مراجعة Console.', 'error');
                    return;
                }

                if (!response.ok) {
                    const errorMessage = responseData.error || responseData.message ||
                        'حدث خطأ غير معروف أثناء حفظ الجدول.';
                    console.error('Error saving timetable slot (Backend response not OK):', responseData);
                    showMessage(errorMessage, 'error');
                    return;
                }

                showMessage(responseData.message, 'success');
                updateCellContent(responseData.timetable_slot); // هذا هو المفتاح للتحديث الصحيح
                closeModal();

            } catch (error) {
                console.error('Fetch operation error:', error);
                showMessage('حدث خطأ في الاتصال بالخادم: ' + error.message, 'error');
            }
        }

         async function clearTimetableSlot() {
        const slotId = document.getElementById('slotId').value;
        const day = document.getElementById('modalDay').value;
        const period = document.getElementById('modalPeriod').value;
        // لا نحتاج departmentId و year هنا إذا كنا سنستخدم فقط ID للحذف
        // const departmentId = document.getElementById('modalDepartmentId').value;
        // const year = document.getElementById('modalYear').value;

        // استبدال confirm() بدالة عرض رسالة إذا كانت هذه ستعمل في بيئة لا تدعم confirm()
        // أو يمكنك استخدام نظام modal مخصص للتأكيد
        if (!slotId) {
            showMessage('هذه الخانة فارغة بالفعل. لا يوجد شيء لمسحه.', 'info');
            closeModal();
            return;
        }

        // استخدام نافذة تأكيد بسيطة (تأكد أنها مدعومة أو استبدلها بمودال مخصص)
        if (!window.confirm('هل أنت متأكد من مسح هذه الحصة من الجدول؟')) {
            return;
        }

        try {
            // **[تعديل هام 1: تغيير المسار إلى مسار الحذف]**
            // **[تعديل هام 2: تغيير طريقة الطلب إلى 'DELETE']**
            // **[تعديل هام 3: إرسال الـ ID فقط في الـ body]**
            const response = await fetch("{{ route('timetable.delete') }}", {
                method: 'DELETE', // يجب أن تكون DELETE لتطابق الـ route
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id: slotId // فقط أرسل الـ ID
                })
            });

            let responseData;
            const responseText = await response.text();
            console.log('Backend Clear Response (Text):', responseText);

            try {
                responseData = JSON.parse(responseText);
            } catch (jsonParseError) {
                console.error('Failed to parse JSON response during clear:', jsonParseError);
                console.error('Non-JSON response during clear:', responseText);
                showMessage('حدث خطأ غير متوقع من الخادم أثناء المسح. الاستجابة ليست JSON. يرجى مراجعة Console.', 'error');
                return;
            }

            if (!response.ok) {
                const errorMessage = responseData.error || responseData.message ||
                    'حدث خطأ غير معروف أثناء مسح الحصة.';
                console.error('Error clearing timetable slot (Backend response not OK):', responseData);
                showMessage(errorMessage, 'error');
                return;
            }

            showMessage(responseData.message, 'success');
            // عند المسح، يجب تحديث الخلية يدويًا في الواجهة الأمامية
            if (currentSelectedCell) {
                currentSelectedCell.innerHTML = `اضغط للإضافة`;
                currentSelectedCell.dataset.id = '';
                currentSelectedCell.dataset.courseId = '';
                currentSelectedCell.dataset.professorId = '';
                currentSelectedCell.dataset.assistantId = '';
                currentSelectedCell.dataset.roomId = ''; // مسح الـ room_id أيضاً من الـ dataset
                currentSelectedCell.classList.add('empty-slot');
                currentSelectedCell.classList.remove('timetable-slot'); // إزالة فئة timetable-slot إذا لم تعد تحتوي على بيانات
            }
            closeModal();

        } catch (error) {
            console.error('Fetch error:', error);
            showMessage('حدث خطأ في الاتصال بالخادم: ' + error.message, 'error');
        }
    }
        // دالة لتحديث محتوى الخلية بعد الحفظ/المسح
         function updateCellContent(slotData) {
        if (!currentSelectedCell) return;

        // Update data- attributes
        currentSelectedCell.dataset.id = slotData ? slotData.id : '';
        currentSelectedCell.dataset.courseId = slotData && slotData.course_id ? slotData.course_id : '';
        currentSelectedCell.dataset.professorId = slotData && slotData.professor_id ? slotData.professor_id : '';
        currentSelectedCell.dataset.assistantId = slotData && slotData.assistant_id ? slotData.assistant_id : '';
        currentSelectedCell.dataset.roomId = slotData && slotData.room_id ? slotData.room_id : '';

        // Update visible content
        if (slotData && slotData.course_id) { // Check for course_id to ensure a valid slot
            const courseName = slotData.course ? slotData.course.course_name : 'غير محدد';
            const professorName = slotData.professor ? `د. ${slotData.professor.full_name}` : '';
            const assistantName = slotData.assistant ? `م. ${slotData.assistant.full_name}` : '';
            const roomName = slotData.room ? `قاعة: ${slotData.room.room_name}` : '';

            currentSelectedCell.innerHTML = `
                <p><span>${courseName}</span></p>
                ${professorName ? `<p>${professorName}</p>` : ''}
                ${assistantName ? `<p>${assistantName}</p>` : ''}
                ${roomName ? `<p>${roomName}</p>` : ''}
            `;
            currentSelectedCell.classList.remove('empty-slot');
            currentSelectedCell.classList.add('timetable-slot'); // إضافة الفئة إذا تم إضافة محتوى
        } else {
            currentSelectedCell.innerHTML = `اضغط للإضافة`;
            currentSelectedCell.classList.add('empty-slot');
            currentSelectedCell.classList.remove('timetable-slot'); // إزالة الفئة إذا كانت فارغة
        }
    }

        // Initial population of instructors when the page loads
        // (This ensures that if a course is pre-selected on page load, instructors are filtered)
        document.addEventListener('DOMContentLoaded', () => {
            // No need to explicitly call filterInstructorsForCourse here on DOMContentLoaded
            // because openModal handles initial population and filtering when a cell is clicked.
            // If the modal is opened for an empty slot, filterInstructorsForCourse will be called with null courseId,
            // populating all instructors.
        });

        // Assuming there might be an 'applyFilter' button from a previous page
        // or a filter section that navigates to this page.
        // This part is likely for the page where you select department and year *before* coming here.
        // It's kept here for completeness in case you have a filter on this page too.
        const applyFilterButton = document.getElementById('applyFilter');

        if (applyFilterButton) {
            applyFilterButton.addEventListener('click', () => {
                const departmentId = document.getElementById('departmentSelect').value;
                const year = document.getElementById('yearSelect').value;

                if (departmentId && year) {
                    window.location.href = `{{ url('timetables/manage') }}/${year}/${departmentId}`;
                } else {
                    showMessage('الرجاء اختيار القسم والسنة.', 'error');
                }
            });
        }
    </script>
</body>

</html>
