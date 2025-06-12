<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Instructor;
use App\Models\Room;
use App\Models\Timetable;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class TimetableController extends Controller
{
    /**
     * يعرض صفحة اختيار السنة الدراسية.
     *
     * @return \Illuminate\View\View
     */
    public function showYearSelection()
    {
        $years = [1, 2, 3, 4]; // السنوات الدراسية المتاحة
        return view('Tables.Timetable', compact('years'));
    }

    /**
     * يعرض جداول الأقسام لسنة دراسية محددة.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $year
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showDepartmentTimetablesForYear(Request $request, $year)
    {
        if (!in_array($year, [1, 2, 3, 4])) {
            return redirect()->route('Tables.Timetable')->with('error', 'السنة المختارة غير صالحة.');
        }

        $departments = Department::all();

        $days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
        $periods = [
            1 => '9:00 - 11:00',
            2 => '11:00 - 1:00',
            3 => '1:00 - 3:00',
            4 => '3:00 - 5:00',
            5 => '5:00 - 7:00',
        ];

        $departmentTimetableData = [];

        foreach ($departments as $department) {
            $timetableSlots = Timetable::where('department_id', $department->id)
                ->where('year', $year)
                ->with(['course', 'professor', 'assistant', 'room'])
                ->get();

            $timetableGrid = [];
            if ($timetableSlots->isNotEmpty()) {
                foreach ($timetableSlots as $slot) {
                    $timetableGrid[$slot->day][$slot->period_number] = $slot;
                }
            }

            $departmentTimetableData[] = [
                'department' => $department,
                'hasTimetable' => $timetableSlots->isNotEmpty(),
                'timetableGrid' => $timetableGrid,
            ];
        }

        return view('Tables.department_list_for_year', compact('year', 'departmentTimetableData', 'days', 'periods'));
    }

    /**
     * يعرض واجهة إدارة الجدول الزمني لقسم وسنة محددة.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $year
     * @param int $department_id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function manageTimetableGrid(Request $request, $year, $department_id)
    {
        $selectedDepartment = Department::find($department_id);

        if (!$selectedDepartment) {
            return redirect()->route('Tables.Timetable')->with('error', 'القسم غير موجود.');
        }

        $days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
        $periods = [
            1 => '9:00 - 11:00',
            2 => '11:00 - 1:00',
            3 => '1:00 - 3:00',
            4 => '3:00 - 5:00',
            5 => '5:00 - 7:00',
        ];

        $timetableSlots = Timetable::where('department_id', $department_id)
            ->where('year', $year)
            ->with(['course', 'professor', 'assistant', 'room'])
            ->get();

        $timetableData = [];
        $usedCourseIds = [];

        foreach ($timetableSlots as $slot) {
            $timetableData[$slot->day][$slot->period_number] = $slot;
            if ($slot->course_id) {
                $usedCourseIds[] = $slot->course_id;
            }
        }
        $usedCourseIds = array_unique($usedCourseIds);

        $availableCourses = $selectedDepartment->courses()->wherePivot('year', $year)->get();

        $professors = Instructor::where('position', 'Professor')->select('id', 'full_name', 'position', 'work_days')->get();
        $assistants = Instructor::where('position', 'Assistant')->select('id', 'full_name', 'position', 'work_days')->get();

        $rooms = Room::all();

        $allDepartments = Department::all();
        $allYears = [1, 2, 3, 4];

        return view('Tables.manage_grid', compact(
            'days',
            'periods',
            'timetableData',
            'availableCourses',
            'selectedDepartment',
            'year',
            'allDepartments',
            'allYears',
            'professors',
            'assistants',
            'rooms',
            'usedCourseIds'
        ));
    }

    /**
     * جلب الأساتذة والمعيدين المرتبطين بمقرر معين.
     *
     * @param int|string $course_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCourseInstructors($course_id)
    {
        // إذا كان course_id فارغًا أو null، نرجع جميع الأساتذة والمعيدين
        if (empty($course_id) || $course_id === 'null') {
            $allInstructors = Instructor::select('id', 'full_name', 'position', 'work_days')
                ->whereIn('position', ['Professor', 'Assistant'])
                ->get();
            Log::info('Returning all instructors as course_id is empty/null.');
            return response()->json($allInstructors);
        }

        $course = Course::find($course_id);

        if (!$course) {
            Log::warning('Course not found for ID: ' . $course_id);
            return response()->json(['error' => 'المقرر غير موجود.'], 404);
        }

        $instructors = $course->instructors()
            ->wherePivotIn('role', ['Professor', 'Assistant'])
            ->select('instructors.id', 'instructors.full_name', 'instructors.position', 'instructors.work_days')
            ->get();

        Log::info('Instructors fetched by getCourseInstructors method for course ' . $course_id . ': ' . $instructors->toJson());

        return response()->json($instructors);
    }

    /**
     * حفظ أو تحديث خانة الجدول الزمني.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveTimetableSlot(Request $request)
    {
        DB::beginTransaction();

        try {
            // قم بالتحقق من صحة البيانات هنا
            // **[التعديل هنا: إضافة مصفوفة الرسائل المخصصة كمعامل ثاني لـ validate()]**
            $data = $request->validate([
                'id' => 'nullable|exists:timetables,id', // For updating existing slot
                'day' => 'required|string',
                'period_number' => 'required|integer',
                'department_id' => 'required|exists:departments,id',
                'year' => 'required|integer',
                'course_id' => 'nullable|exists:courses,id',
                'professor_id' => 'nullable|exists:instructors,id',
                'assistant_id' => 'nullable|exists:instructors,id',
                'room_id' => 'required|exists:rooms,id', // هذا الحقل إجباري الآن
            ], [
                // الرسائل المخصصة لقواعد التحقق
                'room_id.required' => 'يجب اختيار قاعة.', // هذه هي الرسالة التي تريدها
                'day.required' => 'اليوم مطلوب.',
                'period_number.required' => 'الفترة مطلوبة.',
                'department_id.required' => 'القسم مطلوب.',
                'year.required' => 'السنة الدراسية مطلوبة.',
                // يمكنك إضافة رسائل مخصصة لأي قاعدة أخرى هنا بنفس النمط:
                // 'اسم_الحقل.اسم_القاعدة' => 'رسالتك المخصصة',
            ]);

            $slot = $data['id'] ? Timetable::find($data['id']) : new Timetable();

            if (is_null($data['course_id']) && (empty($data['professor_id']) && empty($data['assistant_id']))) {
                 DB::rollBack();
                 return response()->json(['error' => 'يجب تحديد مقرر وأستاذ/معيد على الأقل لحفظ الحصة.'], 400);
            }

            // Check if attempting to assign a course without professor/assistant
            if (!empty($data['course_id']) && empty($data['professor_id']) && empty($data['assistant_id'])) {
                DB::rollBack();
                return response()->json(['error' => 'يجب تحديد أستاذ أو معيد على الأقل للمقرر.'], 400);
            }

            // Validate that only one type (professor or assistant) is selected for the current slot
            $isCurrentSlotLecture = !empty($data['professor_id']);
            $isCurrentSlotSection = !empty($data['assistant_id']);

            if ($isCurrentSlotLecture && $isCurrentSlotSection) {
                DB::rollBack();
                return response()->json(['error' => 'لا يمكن تحديد أستاذ ومعيد لنفس الحصة في نفس الوقت. اختر أحدهما فقط.'], 400);
            }

            // --- 1. Check for maximum course occurrences (max 2 times per course) ---
            if (!empty($data['course_id'])) {
                $courseOccurrences = Timetable::where('course_id', $data['course_id'])
                    ->where('department_id', $data['department_id'])
                    ->where('year', $data['year'])
                    ->when($slot->exists, function ($query) use ($slot) {
                        $query->where('id', '!=', $slot->id);
                    })
                    ->count();

                if ($courseOccurrences >= 2) {
                    DB::rollBack();
                    return response()->json(['error' => 'هذا المقرر موجود بالفعل مرتين في الجدول. لا يمكن إضافته مرة ثالثة.'], 409);
                }

                // --- 2. Check for Lecture vs. Section type based on existing slots ---
                $hasLecture = Timetable::where('course_id', $data['course_id'])
                    ->where('department_id', $data['department_id'])
                    ->where('year', $data['year'])
                    ->whereNotNull('professor_id')
                    ->when($slot->exists, function ($query) use ($slot) {
                        $query->where('id', '!=', $slot->id);
                    })
                    ->exists();

                $hasSection = Timetable::where('course_id', $data['course_id'])
                    ->where('department_id', $data['department_id'])
                    ->where('year', $data['year'])
                    ->whereNotNull('assistant_id')
                    ->when($slot->exists, function ($query) use ($slot) {
                        $query->where('id', '!=', $slot->id);
                    })
                    ->exists();

                if ($isCurrentSlotLecture && $hasLecture) {
                    DB::rollBack();
                    return response()->json(['error' => 'يوجد بالفعل حصة محاضرة لهذا المقرر. لا يمكن إضافة محاضرة أخرى.'], 409);
                } else if ($isCurrentSlotSection && $hasSection) {
                    DB::rollBack();
                    return response()->json(['error' => 'يوجد بالفعل حصة سكشن لهذا المقرر. لا يمكن إضافة سكشن آخر.'], 409);
                }
            } else {
                // If no course is selected, ensure no professor/assistant is selected either
                if (!empty($data['professor_id']) || !empty($data['assistant_id'])) {
                    DB::rollBack();
                    return response()->json(['error' => 'لا يمكن تحديد أستاذ/معيد بدون تحديد مقرر.'], 400);
                }
            }

            // --- 3. Check instructor work days and availability across ALL timetables ---
            if (!empty($data['professor_id']) || !empty($data['assistant_id'])) {
                $instructorId = $data['professor_id'] ?? $data['assistant_id'];
                $instructor = Instructor::find($instructorId);

                if (!$instructor) {
                    DB::rollBack();
                    return response()->json(['error' => 'المعلم المختار غير صالح.'], 400);
                }

                if (is_null($instructor->work_days)) {
                    DB::rollBack();
                    $role = ($instructor->position == 'Professor') ? 'الأستاذ' : 'المعيد';
                    return response()->json(['error' => $role . ' ' . $instructor->full_name . ' أيام عمله غير محددة. يرجى تحديدها أولا.'], 400);
                }

                $workDays = array_map('trim', explode(',', $instructor->work_days));

                if (!in_array($data['day'], $workDays)) {
                    DB::rollBack();
                    $role = ($instructor->position == 'Professor') ? 'الأستاذ' : 'المعيد';
                    return response()->json(['error' => $role . ' ' . $instructor->full_name . ' لا يعمل في يوم ' . $this->mapDayToArabic($data['day']) . '.'], 400);
                }

                // Check for instructor availability across ALL timetables
                $conflictingInstructorSlot = Timetable::where('day', $data['day'])
                    ->where('period_number', $data['period_number'])
                    ->where(function ($query) use ($instructorId) {
                        $query->where('professor_id', $instructorId)
                              ->orWhere('assistant_id', $instructorId);
                    })
                    ->when($slot->exists, function ($query) use ($slot) {
                        $query->where('id', '!=', $slot->id);
                    })
                    ->first();

                if ($conflictingInstructorSlot) {
                    DB::rollBack();
                    $role = ($instructor->position == 'Professor') ? 'الأستاذ' : 'المعيد';
                    return response()->json(['error' => $role . ' ' . $instructor->full_name . ' مشغول بالفعل في هذا الموعد (' . $this->mapDayToArabic($data['day']) . '، فترة ' . $data['period_number'] . ') في جدول آخر.'], 409);
                }
            }

            // --- 4. Check if the slot (day, period, department, year) is already taken (for new slots or if moving) ---
            $conflictingSlot = Timetable::where('day', $data['day'])
                ->where('period_number', $data['period_number'])
                ->where('department_id', $data['department_id'])
                ->where('year', $data['year'])
                ->when($slot->exists, function ($query) use ($slot) {
                    $query->where('id', '!=', $slot->id);
                })
                ->first();

            if ($conflictingSlot) {
                DB::rollBack();
                return response()->json(['error' => 'هذا الموعد (' . $this->mapDayToArabic($data['day']) . '، فترة ' . $data['period_number'] . ') مشغول بالفعل بمقرر آخر في هذا القسم والسنة.'], 409);
            }

            // --- 5. Check if the room is available in this period across ALL timetables ---
            $conflictingRoomSlot = Timetable::where('day', $data['day'])
                ->where('period_number', $data['period_number'])
                ->where('room_id', $data['room_id'])
                ->when($slot->exists, function ($query) use ($slot) {
                    $query->where('id', '!=', $slot->id);
                })
                ->first();

            if ($conflictingRoomSlot) {
                DB::rollBack();
                $room = Room::find($data['room_id']);
                $roomName = $room ? $room->room_name : 'غير معروفة';
                return response()->json(['error' => 'قاعة ' . $roomName . ' مشغولة بالفعل في هذا الموعد (' . $this->mapDayToArabic($data['day']) . '، فترة ' . $data['period_number'] . ') في جدول آخر.'], 409);
            }


            // If all checks pass, save the slot
            $slot->fill($data);
            $slot->save();

            DB::commit();

            // Reload the slot with its relationships to send back to frontend
            $slot->load('course', 'professor', 'assistant', 'room');

            return response()->json([
                'message' => 'تم حفظ الحصة بنجاح.',
                'timetable_slot' => $slot,
                'is_cleared' => false
            ]);

        } catch (ValidationException $e) { // استخدام ValidationException مباشرة
            DB::rollBack();
            // هذا السطر سيستخدم الرسائل المخصصة التي حددتها في معامل validate() الثاني
            return response()->json(['error' => $e->validator->errors()->first()], 400);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving timetable slot: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'حدث خطأ غير متوقع أثناء حفظ الحصة. ' . $e->getMessage()], 500);
        }
    }


    /**
     * يحذف خانة جدول زمني.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTimetableSlot(Request $request)
    {
        // استخدام try-catch حول الـ validate() أيضًا للتعامل مع ValidationException بشكل صريح
        try {
            $request->validate([
                'id' => 'required|exists:timetables,id',
            ]);

            DB::beginTransaction();
            $slot = Timetable::find($request->id);
            if ($slot) {
                $slot->delete();
                DB::commit();
                return response()->json([
                    'message' => 'تم حذف الحصة بنجاح.',
                    'deleted_id' => $request->id,
                    'is_cleared' => true
                ]);
            }
            DB::rollBack();
            return response()->json(['error' => 'الحصة غير موجودة.'], 404);

        } catch (ValidationException $e) {
            // هذا يلتقط أخطاء الـ validation ويرجعها كـ JSON
            DB::rollBack(); // (لا ضرورة للـ rollback هنا في الواقع إذا فشل validate قبل المعاملات)
            return response()->json(['error' => $e->validator->errors()->first()], 400);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting timetable slot: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'حدث خطأ أثناء حذف الحصة.'], 500);
        }
    }

    /**
     * دالة مساعدة لتحويل أسماء الأيام من الإنجليزية إلى العربية.
     *
     * @param string $day
     * @return string
     */
    private function mapDayToArabic(string $day): string
    {
        $dayMap = [
            'Saturday' => 'السبت',
            'Sunday' => 'الأحد',
            'Monday' => 'الاثنين',
            'Tuesday' => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday' => 'الخميس',
            'Friday' => 'الجمعة',
        ];
        return $dayMap[$day] ?? $day;
    }
}
