<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Timetable</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @media print {
            body {
                background-color: #fff;
                margin: 0;
                padding: 0;
            }

            #app {
                box-shadow: none;
                border-radius: 0;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 0;
            }

            th,
            td {
                border: 1px solid #ccc;
                padding: 8px;
                text-align: left;
                font-size: 10px;
            }

            h1 {
                text-align: center;
                margin-bottom: 20px;
            }
        }

        .timetable-slot {
            min-width: 150px;
            vertical-align: top;
            cursor: pointer;
            /* للإشارة إلى أنها قابلة للنقر */
        }

        /* Style for the message display area */
        .message-area {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            /* Green for success */
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            display: none;
            /* Hidden by default */
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .message-area.error {
            background-color: #f44336;
            /* Red for error */
        }

        .message-area.show {
            display: block;
            opacity: 1;
        }
    </style>
</head>

<body class="bg-gray-100 p-8">
    <div id="app" class="container mx-auto bg-white rounded shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">University Timetable Management</h1>

        <div class="flex space-x-4 mb-6 no-print">
            <div>
                <label for="department_filter" class="block text-gray-700 text-sm font-bold mb-2">Department:</label>
                <select id="department_filter"
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Select Department</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="year_filter" class="block text-gray-700 text-sm font-bold mb-2">Year:</label>
                <select id="year_filter"
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="">Select Year</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <button id="apply_filter"
                class="mt-7 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Apply Filter
            </button>
        </div>

        {{-- منطقة عرض الرسائل --}}
        <div id="message_area" class="message-area"></div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b text-center">Day / Period</th>
                        @foreach ($periods as $periodNum => $periodTime)
                            <th class="py-2 px-4 border-b text-center">{{ $periodTime }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="timetable_body">
                    @foreach ($days as $day)
                        <tr>
                            <td class="py-2 px-4 border-b font-semibold text-center">{{ $day }}</td>
                            @foreach ($periods as $periodNum => $periodTime)
                                <td class="py-2 px-4 border-b timetable-slot text-center" data-day="{{ $day }}"
                                    data-period="{{ $periodNum }}">
                                    {{-- Content will be dynamically filled by JS --}}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="actions-section flex justify-center space-x-4 mt-6 no-print">
            <button id="print_btn"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                Print Timetable
            </button>
        </div>

        <div id="edit_modal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white p-8 rounded shadow-lg w-1/3">
                <h2 class="text-xl font-bold mb-4">Edit Timetable Slot</h2>
                <input type="hidden" id="modal_id"> {{-- Add this for existing slot ID --}}
                <input type="hidden" id="modal_day">
                <input type="hidden" id="modal_period">
                <input type="hidden" id="modal_department_id">
                <input type="hidden" id="modal_year">

                <div class="mb-4">
                    <label for="modal_course" class="block text-gray-700 text-sm font-bold mb-2">Course:</label>
                    <select id="modal_course"
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Course</option>
                        {{-- Options will be populated by JavaScript --}}
                    </select>
                </div>

                <div class="mb-4">
                    <label for="modal_professor" class="block text-gray-700 text-sm font-bold mb-2">Professor:</label>
                    <select id="modal_professor"
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Professor</option>
                        {{-- Options will be populated by JavaScript --}}
                    </select>
                </div>

                <div class="mb-4">
                    <label for="modal_assistant" class="block text-gray-700 text-sm font-bold mb-2">Assistant:</label>
                    <select id="modal_assistant"
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Assistant</option>
                        {{-- Options will be populated by JavaScript --}}
                    </select>
                </div>

                <div class="mb-4">
                    <label for="modal_room" class="block text-gray-700 text-sm font-bold mb-2">Room:</label>
                    <select id="modal_room"
                        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Room</option>
                        {{-- Options will be populated by JavaScript --}}
                    </select>
                </div>

                <div class="flex justify-end space-x-4">
                    <button id="save_slot_btn"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save</button>
                    <button id="cancel_edit_btn"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Cancel</button>
                    <button id="clear_slot_btn"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Clear
                        Slot</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تعريف عناصر DOM
            const departmentFilter = document.getElementById('department_filter');
            const yearFilter = document.getElementById('year_filter');
            const applyFilterBtn = document.getElementById('apply_filter');
            const timetableBody = document.getElementById('timetable_body');
            const editModal = document.getElementById('edit_modal');
            const modalIdInput = document.getElementById('modal_id'); // Added: Input for current slot ID
            const modalCourseSelect = document.getElementById('modal_course');
            const modalProfessorSelect = document.getElementById('modal_professor');
            const modalAssistantSelect = document.getElementById('modal_assistant');
            const modalRoomSelect = document.getElementById('modal_room');
            const saveSlotBtn = document.getElementById('save_slot_btn');
            const cancelEditBtn = document.getElementById('cancel_edit_btn');
            const clearSlotBtn = document.getElementById('clear_slot_btn');
            const printBtn = document.getElementById('print_btn');
            const messageArea = document.getElementById('message_area');

            // متغيرات الحالة
            let allAvailableCourses = [];
            let currentTimetableData = {};
            let messageTimeout;
            let allAvailableRooms = []; // لتخزين جميع القاعات المتاحة
            let allInstructorsForSelectedCourse = []; // لتخزين جميع المدربين (دكاترة ومعيدين) للمادة المختارة

            // 1. جلب جميع القاعات المتاحة من API
            async function fetchAllRooms() {
                try {
                    const response = await fetch('/api/rooms/all');
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();
                    allAvailableRooms = data; // تخزين القاعات في المتغير العام
                } catch (error) {
                    console.error('حدث خطأ أثناء جلب بيانات القاعات:', error);
                    showMessage(
                        'حدث خطأ أثناء تحميل بيانات القاعات. يرجى التأكد من اتصال الشبكة وإعادة المحاولة.',
                        'error');
                }
            }

            // استدعاء جلب القاعات عند تحميل الصفحة
            fetchAllRooms();

            // معالجة الأحداث
            applyFilterBtn.addEventListener('click', fetchTimetableData);

            timetableBody.addEventListener('click', function(event) {
                const slot = event.target.closest('.timetable-slot');
                if (slot) {
                    const departmentId = departmentFilter.value;
                    const year = yearFilter.value;

                    if (!departmentId || !year) {
                        showMessage('لفتح خانة الجدول، يرجى تحديد القسم والسنة أولاً من الفلاتر أعلاه.',
                            'error');
                        return;
                    }

                    const day = slot.dataset.day;
                    const period = slot.dataset.period;

                    // تعيين قيم الفتحات الحالية في الـ modal
                    document.getElementById('modal_day').value = day;
                    document.getElementById('modal_period').value = period;
                    document.getElementById('modal_department_id').value = departmentId;
                    document.getElementById('modal_year').value = year;

                    // الحصول على بيانات الفتحة من الكائن currentTimetableData
                    const slotData = currentTimetableData[day] && currentTimetableData[day][period] ?
                        currentTimetableData[day][period] : {};

                    // تعيين ID للفتحة إذا كانت موجودة (لتحديثها)
                    modalIdInput.value = slotData.id || '';

                    // الحصول على IDs الحالية للكورس والدكتور والمعيد والقاعة
                    const currentCourseId = slotData.course ? slotData.course.id : '';
                    const currentProfessorId = slotData.professor ? slotData.professor.id : '';
                    const currentAssistantId = slotData.assistant ? slotData.assistant.id : '';
                    const currentRoomId = slotData.room ? slotData.room.id : '';

                    // تعبئة قوائم الـ dropdowns في الـ modal
                    populateModalDropdowns(currentCourseId, currentProfessorId, currentAssistantId,
                        currentRoomId, day); // تمرير 'day' لتصفية المدربين حسب أيام عملهم

                    editModal.classList.remove('hidden'); // إظهار الـ modal
                }
            });

            cancelEditBtn.addEventListener('click', () => {
                editModal.classList.add('hidden'); // إخفاء الـ modal
                showMessage('تم إلغاء عملية التعديل/الإضافة.', 'info');
            });

            saveSlotBtn.addEventListener('click', saveTimetableSlot);
            clearSlotBtn.addEventListener('click', clearTimetableSlot);

            // عند تغيير المادة في الـ modal، قم بتحديث الدكاترة والمعيدين
            modalCourseSelect.addEventListener('change', function() {
                const selectedCourseId = this.value;
                const currentDay = document.getElementById('modal_day').value;
                // لا نمرر currentProfessorId أو currentAssistantId هنا لأننا نعيد بناء القوائم بالكامل
                populateInstructorsForCourse(selectedCourseId, '', '', currentDay);
            });

            // عند تغيير الدكتور في الـ modal، تحقق من أيام عمله
            modalProfessorSelect.addEventListener('change', function() {
                const selectedProfessorId = this.value;
                const currentDay = document.getElementById('modal_day').value;
                checkInstructorAvailability(selectedProfessorId, currentDay, 'دكتور');
            });

            // عند تغيير المعيد في الـ modal، تحقق من أيام عمله
            modalAssistantSelect.addEventListener('change', function() {
                const selectedAssistantId = this.value;
                const currentDay = document.getElementById('modal_day').value;
                checkInstructorAvailability(selectedAssistantId, currentDay, 'معيد');
            });

            printBtn.addEventListener('click', () => {
                window.print();
            });

            // دالة لعرض الرسائل للمستخدم
            function showMessage(msg, type = 'success') {
                if (messageTimeout) {
                    clearTimeout(messageTimeout);
                }

                messageArea.textContent = msg;
                messageArea.classList.remove('success', 'error', 'info'); // Add 'info' if you use it
                messageArea.classList.add(type, 'show');
                messageArea.style.display = 'block'; // التأكد من أن الرسالة مرئية

                messageTimeout = setTimeout(() => {
                    messageArea.classList.remove('show');
                    setTimeout(() => messageArea.style.display = 'none', 500);
                }, 5000);
            }

            // 2. جلب بيانات الجدول الزمني من API
            async function fetchTimetableData() {
                const departmentId = departmentFilter.value;
                const year = yearFilter.value;

                if (!departmentId || !year) {
                    showMessage('يرجى تحديد القسم والسنة لعرض الجدول الزمني.', 'info');
                    renderTimetable({}); // عرض جدول فارغ إذا لم يتم تحديد القسم والسنة
                    allAvailableCourses = [];
                    currentTimetableData = {};
                    return;
                }

                try {
                    const response = await fetch(`/api/timetable?department_id=${departmentId}&year=${year}`);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const data = await response.json();

                    allAvailableCourses = data.availableCourses; // تخزين الكورسات المتاحة
                    currentTimetableData = data.timetableData; // تخزين بيانات الجدول الزمني

                    renderTimetable(currentTimetableData); // عرض الجدول
                    showMessage('تم تحميل بيانات الجدول بنجاح!', 'success');
                } catch (error) {
                    console.error('حدث خطأ أثناء جلب بيانات الجدول الزمني:', error);
                    showMessage('فشل تحميل بيانات الجدول الزمني. يرجى التحقق من اتصال الشبكة وإعادة المحاولة.',
                        'error');
                    renderTimetable({}); // عرض جدول فارغ في حالة الخطأ
                }
            }

            // 3. عرض بيانات الجدول الزمني في الواجهة
            function renderTimetable(timetableData) {
                // إعادة تعيين جميع خلايا الجدول
                document.querySelectorAll('.timetable-slot').forEach(cell => {
                    cell.innerHTML = `
                    <div class="text-gray-500">
                        <p class="course-name" data-course-id=""></p>
                        <p class="instructor-name text-sm" data-instructor-id=""></p>
                        <p class="assistant-name text-sm" data-instructor-id=""></p>
                        <p class="room-name text-xs"></p>
                    </div>
                    <button class="edit-btn text-blue-500 hover:underline text-sm hidden">تعديل</button>
                `;
                    cell.querySelector('.edit-btn').classList.remove('hidden'); // إظهار زر التعديل
                });

                // تعبئة الخلايا بالبيانات الفعلية
                for (const day in timetableData) {
                    for (const periodNum in timetableData[day]) {
                        const slotData = timetableData[day][periodNum];
                        const cell = document.querySelector(
                            `.timetable-slot[data-day="${day}"][data-period="${periodNum}"]`);

                        if (cell) {
                            const courseNameP = cell.querySelector('.course-name');
                            const instructorNameP = cell.querySelector('.instructor-name');
                            const assistantNameP = cell.querySelector('.assistant-name');
                            const roomNameP = cell.querySelector('.room-name');
                            const textDiv = cell.querySelector('div');

                            if (slotData.course) {
                                courseNameP.textContent = slotData.course.course_name;
                                courseNameP.dataset.courseId = slotData.course.id;
                                textDiv.classList.remove('text-gray-500');
                                textDiv.classList.add('text-black');
                            } else {
                                courseNameP.textContent = 'خانة فارغة';
                                courseNameP.dataset.courseId = '';
                                textDiv.classList.add('text-gray-500');
                                textDiv.classList.remove('text-black');
                            }

                            if (slotData.professor) {
                                instructorNameP.textContent = slotData.professor.full_name;
                                instructorNameP.dataset.instructorId = slotData.professor.id;
                            } else {
                                instructorNameP.textContent = '';
                                instructorNameP.dataset.instructorId = '';
                            }

                            if (slotData.assistant) {
                                assistantNameP.textContent = 'م. ' + slotData.assistant.full_name;
                                assistantNameP.dataset.instructorId = slotData.assistant.id;
                            } else {
                                assistantNameP.textContent = '';
                                assistantNameP.dataset.instructorId = '';
                            }

                            if (slotData.room) {
                                roomNameP.textContent = 'قاعة: ' + slotData.room.room_name;
                            } else {
                                roomNameP.textContent = '';
                            }
                        }
                    }
                }
            }

            // 4. تعبئة قوائم الدكاترة والمعيدين للمادة المختارة (عرض الكل أولاً)
            async function populateInstructorsForCourse(selectedCourseId, currentProfessorId, currentAssistantId,
                currentDay) {
                // قم بإفراغ القوائم أولاً
                modalProfessorSelect.innerHTML = '<option value="">اختر دكتور</option>';
                modalAssistantSelect.innerHTML = '<option value="">اختر معيد</option>';
                allInstructorsForSelectedCourse = []; // تفريغ المدربين السابقين

                if (!selectedCourseId) {
                    return; // إذا لم يتم اختيار مادة، لا تفعل شيئًا
                }

                try {
                    const response = await fetch(`/api/course/${selectedCourseId}/instructors`);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const instructors = await response.json();
                    allInstructorsForSelectedCourse = instructors; // تخزين جميع المدربين للمادة

                    instructors.forEach(instructor => {
                        const option = document.createElement('option');
                        option.value = instructor.id;
                        option.textContent = instructor.full_name;

                        if (instructor.position === 'Professor') {
                            if (instructor.id == currentProfessorId) {
                                option.selected = true;
                            }
                            modalProfessorSelect.appendChild(option);
                        } else if (instructor.position === 'Assistant') {
                            if (instructor.id == currentAssistantId) {
                                option.selected = true;
                            }
                            modalAssistantSelect.appendChild(option);
                        }
                    });

                    // بعد تعبئة القوائم، تحقق من توافر الدكتور/المعيد الحاليين (إذا كانا موجودين)
                    if (currentProfessorId) {
                        checkInstructorAvailability(currentProfessorId, currentDay, 'دكتور');
                    }
                    if (currentAssistantId) {
                        checkInstructorAvailability(currentAssistantId, currentDay, 'معيد');
                    }

                } catch (error) {
                    console.error('حدث خطأ أثناء جلب المدربين للمادة:', error);
                    showMessage('فشل تحميل المدربين لهذه المادة. يرجى التأكد من بيانات المادة.', 'error');
                }
            }

            // دالة للتحقق من توافر الدكتور/المعيد في اليوم المحدد
            function checkInstructorAvailability(instructorId, currentDay, position) {
                if (!instructorId) return;

                const instructor = allInstructorsForSelectedCourse.find(inst => inst.id == instructorId);

                if (instructor && instructor.work_days) {
                    const workDaysArray = instructor.work_days; // نفترض أنها مصفوفة جاهزة

                    if (Array.isArray(workDaysArray) && !workDaysArray.includes(currentDay)) {
                        showMessage(
                            `تنبيه: ${position} ${instructor.full_name} غير متاح للعمل في يوم ${currentDay}. يرجى مراجعة أيامه المحددة.`,
                            'error');
                    } else if (!Array.isArray(workDaysArray)) {
                        console.warn(`أيام عمل ${instructor.full_name} ليست مصفوفة:`, workDaysArray);
                        showMessage(
                            `تنبيه: تعذر التحقق من أيام عمل ${position} ${instructor.full_name}. قد تكون البيانات غير صحيحة.`,
                            'error');
                    }
                }
            }


            // 5. تعبئة جميع قوائم الـ dropdowns في الـ modal عند الفتح
            async function populateModalDropdowns(currentCourseId, currentProfessorId, currentAssistantId,
                currentRoomId, currentDay) {
                // إفراغ وتعبئة قائمة المواد
                modalCourseSelect.innerHTML = '<option value="">اختر مادة</option>';
                allAvailableCourses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    option.textContent = course.course_name;
                    if (course.id == currentCourseId) {
                        option.selected = true;
                    }
                    modalCourseSelect.appendChild(option);
                });

                // إفراغ وتعبئة قائمة القاعات
                modalRoomSelect.innerHTML = '<option value="">اختر قاعة</option>';
                allAvailableRooms.forEach(room => {
                    const option = document.createElement('option');
                    option.value = room.id;
                    option.textContent = room.room_name;
                    if (room.id == currentRoomId) {
                        option.selected = true;
                    }
                    modalRoomSelect.appendChild(option);
                });

                // استدعاء populateInstructorsForCourse هنا بعد تعبئة الكورسات
                // وذلك لضمان جلب الدكاترة والمعيدين فور فتح المودال بناءً على الكورس الحالي واليوم الحالي
                if (currentCourseId) {
                    await populateInstructorsForCourse(currentCourseId, currentProfessorId, currentAssistantId,
                        currentDay);
                } else {
                    // إذا لم يكن هناك كورس محدد، قم بإفراغ قوائم الدكاترة والمعيدين فقط
                    modalProfessorSelect.innerHTML = '<option value="">اختر دكتور</option>';
                    modalAssistantSelect.innerHTML = '<option value="">اختر معيد</option>';
                }
            }

            // 6. حفظ (أو تحديث) فتحة الجدول الزمني
            async function saveTimetableSlot() {
                const departmentId = document.getElementById('modal_department_id').value;
                const year = document.getElementById('modal_year').value;
                const day = document.getElementById('modal_day').value;
                const period = document.getElementById('modal_period').value;
                const courseId = modalCourseSelect.value || null;
                const professorId = modalProfessorSelect.value || null;
                const assistantId = modalAssistantSelect.value || null;
                const roomId = modalRoomSelect.value || null; // جلب قيمة القاعة
                const slotId = modalIdInput.value || null; // جلب الـ ID للفتحة الموجودة (إذا كانت تحديث)

                // التحقق من أن القاعة ليست فارغة (مطلوبة دائمًا)
                if (!roomId) {
                    showMessage('الرجاء اختيار قاعة لهذه الخانة. لا يمكن حفظ الخانة بدون تحديد قاعة.', 'error');
                    return; // إيقاف العملية إذا لم يتم اختيار قاعة
                }

                // تحقق من توافر الدكتور/المعيد المختار قبل الحفظ
                if (professorId) {
                    const professor = allInstructorsForSelectedCourse.find(inst => inst.id == professorId &&
                        inst.position === 'Professor');
                    if (professor && Array.isArray(professor.work_days) && !professor.work_days.includes(day)) {
                        showMessage(
                            `لا يمكن حفظ الخانة: الدكتور ${professor.full_name} غير متاح في يوم ${day}. يرجى اختيار دكتور آخر أو مراجعة أيام عمله.`,
                            'error');
                        return;
                    }
                }

                if (assistantId) {
                    const assistant = allInstructorsForSelectedCourse.find(inst => inst.id == assistantId &&
                        inst.position === 'Assistant');
                    if (assistant && Array.isArray(assistant.work_days) && !assistant.work_days.includes(day)) {
                        showMessage(
                            `لا يمكن حفظ الخانة: المعيد ${assistant.full_name} غير متاح في يوم ${day}. يرجى اختيار معيد آخر أو مراجعة أيام عمله.`,
                            'error');
                        return;
                    }
                }

                try {
                    const response = await fetch('/api/timetable/save', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            id: slotId, // تمرير الـ ID لـ updateOrCreate في Laravel
                            department_id: departmentId,
                            year: year,
                            day: day,
                            period_number: period,
                            course_id: courseId,
                            professor_id: professorId,
                            assistant_id: assistantId,
                            room_id: roomId, // إرسال قيمة القاعة (الآن مطلوبة دائمًا)
                        })
                    });

                    // التعديل هنا: التحقق من نوع الاستجابة
                    // ... داخل الـ fetch .then(async response => { ...
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('استجابة API غير ناجحة (نص الخطأ الكامل):', errorText);
                        try {
                            const errorData = JSON.parse(errorText);
                            let errorMessage = 'فشل حفظ خانة الجدول الزمني.';

                            // **هذا هو التعديل الجديد: التحقق من مفتاح 'error'**
                            if (errorData.error) { // إذا كان الخطأ هو رسالة مباشرة (مثل تعارض القاعة/الأستاذ)
                                errorMessage = `خطأ: ${errorData.error}`;
                            } else if (errorData.message) { // إذا كان الخطأ من ValidationException (Laravel)
                                errorMessage = `فشل الحفظ: ${errorData.message}`;
                            } else if (errorData.errors) { // إذا كانت هناك أخطاء تحقق مفصلة
                                // حاول عرض أول خطأ تحقق من صحة البيانات
                                errorMessage = `خطأ في البيانات: ${Object.values(errorData.errors)[0][0]}`;
                            }
                            showMessage(errorMessage, 'error');
                        } catch (e) {
                            console.error('خطأ في تحليل استجابة السيرفر كـ JSON:', e);
                            showMessage(
                                'حدث خطأ غير متوقع في السيرفر أثناء الحفظ. يرجى مراجعة سجلات الخادم للمزيد من التفاصيل.',
                                'error');
                        }
                        return;
                    }
                    // ... باقي الكود لمعالجة الاستجابة الناجحة

                    // إذا كانت الاستجابة OK، كمل عادي واقراها كـ JSON
                    const data = await response.json();
                    showMessage(data.message, 'success');
                    editModal.classList.add('hidden'); // إخفاء الـ modal بعد الحفظ
                    fetchTimetableData(); // تحديث الجدول لعرض التغييرات
                } catch (error) {
                    console.error('حدث خطأ أثناء حفظ فتحة الجدول الزمني:', error);
                    showMessage(
                        'حدث خطأ في الاتصال بالخادم أثناء الحفظ. يرجى التحقق من الشبكة والمحاولة مرة أخرى.',
                        'error');
                }
            }

            // 7. مسح فتحة الجدول الزمني (باستخدام DELETE request)
            async function clearTimetableSlot() {
                const slotId = modalIdInput.value; // جلب الـ ID للفتحة الموجودة

                // تأكيد من المستخدم قبل المسح
                if (!confirm(
                        'هل أنت متأكد أنك تريد مسح هذه الخانة بالكامل؟ سيتم حذف جميع بياناتها ولن يمكن التراجع عن هذا الإجراء.'
                        )) {
                    showMessage('تم إلغاء عملية المسح.', 'info');
                    return;
                }

                if (!slotId) {
                    showMessage('لا يمكن مسح خانة غير موجودة أو بدون معرف.', 'error');
                    return;
                }

                try {
                    // إرسال طلب DELETE إلى نقطة نهاية جديدة للمسح
                    const response = await fetch(
                    `/api/timetable/${slotId}`, { // <-- تغيير هنا: المسار ونوع الطلب
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]') // CSRF token مطلوب
                                .getAttribute('content')
                        },
                        // لا حاجة لإرسال body مع طلب DELETE إذا كان الـ backend يعتمد على الـ ID في المسار
                    });

                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('استجابة API غير ناجحة (نص الخطأ الكامل) لمسح الخانة:', errorText);
                        try {
                            const errorData = JSON.parse(errorText);
                            let errorMessage = 'فشل مسح خانة الجدول الزمني.';
                            if (errorData.message) {
                                errorMessage = `فشل المسح: ${errorData.message}`;
                            } else if (errorData.errors) {
                                errorMessage = `خطأ في البيانات: ${Object.values(errorData.errors)[0][0]}`;
                            }
                            showMessage(errorMessage, 'error');
                        } catch (e) {
                            showMessage(
                                'حدث خطأ غير متوقع في السيرفر أثناء المسح. يرجى مراجعة سجلات الخادم للمزيد من التفاصيل.',
                                'error');
                        }
                        return;
                    }

                    const data = await response.json();
                    showMessage(data.message || 'تم مسح الخانة بنجاح.',
                    'success'); // رسالة افتراضية لو لم يرسل الـ backend رسالة
                    editModal.classList.add('hidden'); // إخفاء الـ modal بعد المسح
                    fetchTimetableData(); // تحديث الجدول لعرض التغييرات
                } catch (error) {
                    console.error('حدث خطأ أثناء مسح فتحة الجدول الزمني:', error);
                    showMessage(
                        'حدث خطأ في الاتصال بالخادم أثناء المسح. يرجى التحقق من الشبكة والمحاولة مرة أخرى.',
                        'error');
                }
            }

            // استدعاء جلب البيانات الأولية عند تحميل الصفحة
            fetchTimetableData();
        });
    </script>

</body>

</html>
