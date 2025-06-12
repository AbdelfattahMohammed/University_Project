<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Instructor;
use App\Models\Room;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class TableController extends Controller
{
    /**
     * عرض صفحة إنشاء الجدول الزمني.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $departments = Department::all();
        $years = DB::table('course_department')
            ->distinct()
            ->pluck('year')
            ->sort();
        $days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
        $periods = [1 => '9:00-11:00', 2 => '11:00-1:00', 3 => '1:00-3:00', 4 => '3:00-5:00', 5 => '5:00-7:00'];

        return view('Tables.create_table', compact('departments', 'years', 'days', 'periods'));
    }

    /**
     * جلب بيانات الجدول الزمني بناءً على القسم والسنة.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTimetableData(Request $request)
    {
        Log::info('طلب جلب بيانات الجدول:', $request->all());

        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'year' => 'required|integer',
        ], [
            'department_id.required' => 'يجب اختيار القسم.',
            'department_id.exists' => 'القسم المحدد غير موجود.',
            'year.required' => 'يجب تحديد السنة.',
            'year.integer' => 'السنة يجب أن تكون رقمًا صحيحًا.',
        ]);

        $departmentId = $request->input('department_id');
        $year = $request->input('year');

        Log::info("رقم القسم: {$departmentId}, السنة: {$year}");

        $coursesForDeptAndYear = DB::table('course_department')
            ->join('courses', 'course_department.course_id', '=', 'courses.id')
            ->where('course_department.department_id', $departmentId)
            ->where('course_department.year', $year)
            ->select('courses.id', 'courses.course_name', 'course_department.year')
            ->get();

        $savedTimetableEntries = Timetable::with(['course', 'professor', 'assistant', 'room'])
            ->where('department_id', $departmentId)
            ->where('year', $year)
            ->get();

        $days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
        $periods = [1, 2, 3, 4, 5];

        $formattedTimetableData = [];

        foreach ($days as $day) {
            foreach ($periods as $periodNum) {
                $formattedTimetableData[$day][$periodNum] = [
                    'id' => null,
                    'course' => null,
                    'professor' => null,
                    'assistant' => null,
                    'room' => null,
                    'is_saved' => false
                ];

                $foundEntry = $savedTimetableEntries->first(function ($entry) use ($day, $periodNum) {
                    return $entry->day === $day && $entry->period_number === (int)$periodNum;
                });

                if ($foundEntry) {
                    $formattedTimetableData[$day][$periodNum] = [
                        'id' => $foundEntry->id,
                        'course' => $foundEntry->course,
                        'professor' => $foundEntry->professor,
                        'assistant' => $foundEntry->assistant,
                        'room' => $foundEntry->room,
                        'is_saved' => true
                    ];
                }
            }
        }

        return response()->json([
            'availableCourses' => $coursesForDeptAndYear,
            'timetableData' => $formattedTimetableData,
        ]);
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
            Log::info('بيانات الطلب المستلمة لـ saveTimetableSlot:', $request->all());

            $data = $request->validate([
                'id' => 'nullable|exists:timetables,id', // For updating existing slot
                'day' => ['required', 'string', Rule::in(['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'])],
                'period_number' => 'required|integer|min:1|max:5',
                'department_id' => 'required|exists:departments,id',
                'year' => 'required|integer',
                'course_id' => 'nullable|exists:courses,id',
                'professor_id' => 'nullable|exists:instructors,id',
                'assistant_id' => 'nullable|exists:instructors,id',
                'room_id' => 'required|exists:rooms,id', // أصبح إجباريًا دائمًا
            ], [
                'room_id.required' => 'يجب اختيار قاعة.',
                'room_id.exists' => 'القاعة المحددة غير موجودة.',
                'day.required' => 'اليوم مطلوب.',
                'day.string' => 'صيغة اليوم غير صالحة.',
                'day.in' => 'اليوم المحدد غير صالح.',
                'period_number.required' => 'الفترة مطلوبة.',
                'period_number.integer' => 'الفترة يجب أن تكون رقمًا صحيحًا.',
                'period_number.min' => 'رقم الفترة يجب أن يكون 1 على الأقل.',
                'period_number.max' => 'رقم الفترة يجب أن يكون 5 على الأكثر.',
                'department_id.required' => 'القسم مطلوب.',
                'department_id.exists' => 'القسم المحدد غير موجود.',
                'year.required' => 'السنة الدراسية مطلوبة.',
                'year.integer' => 'السنة الدراسية يجب أن تكون رقمًا صحيحًا.',
                'course_id.exists' => 'المادة المحددة غير موجودة.',
                'professor_id.exists' => 'الأستاذ المحدد غير موجود.',
                'assistant_id.exists' => 'المعيد المحدد غير موجود.',
            ]);

            $slot = $data['id'] ? Timetable::find($data['id']) : new Timetable();

            // *** تم إزالة منطق مسح الخانة من هنا. saveTimetableSlot ستقوم الآن بالحفظ أو التحديث فقط ***

            // **تطبيق الشروط اليدوية بالترتيب**

            // 1. يجب تحديد مقرر وأستاذ/معيد على الأقل
            // (بما أن room_id أصبح مطلوبًا دائمًا، فإن هذا الشرط سيمنع حفظ خانة فارغة تمامًا)
            if (is_null($data['course_id']) && (empty($data['professor_id']) && empty($data['assistant_id']))) {
                 DB::rollBack();
                 return response()->json(['error' => 'يجب تحديد مقرر وأستاذ/معيد على الأقل لحفظ الحصة.'], 400);
            }

            // 2. إذا تم تحديد مقرر، يجب تحديد أستاذ أو معيد على الأقل
            if (!empty($data['course_id']) && empty($data['professor_id']) && empty($data['assistant_id'])) {
                DB::rollBack();
                return response()->json(['error' => 'يجب تحديد أستاذ أو معيد على الأقل للمقرر.'], 400);
            }

            // 3. التحقق من تحديد أستاذ ومعيد لنفس الحصة في نفس الوقت
            $isCurrentSlotLecture = !empty($data['professor_id']);
            $isCurrentSlotSection = !empty($data['assistant_id']);

            if ($isCurrentSlotLecture && $isCurrentSlotSection) {
                DB::rollBack();
                return response()->json(['error' => 'لا يمكن تحديد أستاذ ومعيد لنفس الحصة في نفس الوقت. اختر أحدهما فقط.'], 400);
            }

            // 4. التحقق من تكرار المقرر (بحد أقصى مرتين)
            if (!empty($data['course_id'])) {
                $courseOccurrences = Timetable::where('course_id', $data['course_id'])
                    ->where('department_id', $data['department_id'])
                    ->where('year', $data['year'])
                    ->when($slot->exists, function ($query) use ($slot) {
                        $query->where('id', '!=', $slot->id);
                    })
                    ->count();

                // إذا كان المقرر هو نفسه في الخانة الحالية ويتم تحديثه، لا نحسبه كتكرار إضافي
                if ($slot->exists && $slot->course_id === $data['course_id']) {
                    if ($courseOccurrences >= 2) {
                        if ($courseOccurrences > 2) {
                             DB::rollBack();
                             return response()->json(['error' => 'هذا المقرر موجود بالفعل مرتين أو أكثر في الجدول. لا يمكن إضافته مرة ثالثة.'], 409);
                        }
                    }
                } elseif ($courseOccurrences >= 2) {
                    DB::rollBack();
                    return response()->json(['error' => 'هذا المقرر موجود بالفعل مرتين في الجدول. لا يمكن إضافته مرة ثالثة.'], 409);
                }

                // 5. التحقق من نوع الحصة (محاضرة/سكشن) بناءً على الموجود
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
                // إذا لم يتم تحديد مقرر، تأكد من عدم تحديد أستاذ/معيد أيضًا
                if (!empty($data['professor_id']) || !empty($data['assistant_id'])) {
                    DB::rollBack();
                    return response()->json(['error' => 'لا يمكن تحديد أستاذ/معيد بدون تحديد مقرر.'], 400);
                }
            }

            // 6. التحقق من أيام عمل الأستاذ/المعيد وتوافره عبر جميع الجداول
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

                // التحقق من تداخل الأستاذ/المعيد في جدول آخر (بصرف النظر عن القسم والسنة)
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

            // 7. التحقق مما إذا كانت الخانة (اليوم، الفترة، القسم، السنة) مشغولة بالفعل
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

            // 8. التحقق مما إذا كانت القاعة متاحة في هذه الفترة عبر جميع الجداول
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

            // إذا مرت جميع الفحوصات، يتم حفظ الخانة
            $slot->fill($data);
            $slot->save();

            DB::commit();

            // إعادة تحميل الخانة بعلاقاتها لإرسالها للـ frontend
            $slot->load('course', 'professor', 'assistant', 'room');

            return response()->json([
                'message' => 'تم حفظ الحصة بنجاح.',
                'timetable_slot' => $slot,
                'is_cleared' => false
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('خطأ في التحقق من البيانات في saveTimetableSlot:', ['errors' => $e->errors(), 'request' => $request->all()]);
            return response()->json([
                'message' => 'حدث خطأ في البيانات المدخلة.',
                'error' => $e->validator->errors()->first(),
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('خطأ غير متوقع في saveTimetableSlot:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'request' => $request->all()]);
            return response()->json([
                'message' => 'حدث خطأ غير متوقع أثناء حفظ الحصة.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف خانة جدول زمني محددة.
     *
     * @param int $id معرف خانة الجدول الزمني المراد حذفها.
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTimetableSlot($id)
    {
        Log::info('طلب حذف خانة الجدول:', ['id' => $id]);
        DB::beginTransaction();
        try {
            $slot = Timetable::find($id);

            if (!$slot) {
                Log::warning('محاولة حذف خانة جدول غير موجودة.', ['id' => $id]);
                DB::rollBack();
                return response()->json(['message' => 'خانة الجدول غير موجودة.'], 404);
            }

            $slot->delete();
            DB::commit();
            Log::info('تم حذف خانة الجدول بنجاح.', ['id' => $id]);

            return response()->json(['message' => 'تم مسح حصة الجدول بنجاح.']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('خطأ غير متوقع أثناء حذف خانة الجدول:', ['message' => $e->getMessage(), 'id' => $id]);
            return response()->json(['message' => 'حدث خطأ أثناء مسح حصة الجدول.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * جلب الأساتذة والمعيدين المرتبطين بمقرر معين.
     *
     * @param \App\Models\Course $course
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCourseInstructors(Course $course)
    {
        // إذا كان course_id فارغًا أو null (قيمة 'null' كسلسلة نصية من الواجهة الأمامية)
        if (empty($course->id) || $course->id === 'null') {
            $allInstructors = Instructor::select('id', 'full_name', 'position', 'work_days')
                ->whereIn('position', ['Professor', 'Assistant'])
                ->get();
            Log::info('Returning all instructors as course_id is empty/null.');
            return response()->json($allInstructors);
        }

        $instructors = $course->instructors()->get(['instructors.id', 'full_name', 'position', 'work_days']);
        $instructors->each(function ($instructor) {
            Log::info('work_days الأصلية من قاعدة البيانات (قبل المعالجة):', [
                'الاسم' => $instructor->full_name,
                'القيمة' => $instructor->work_days,
                'النوع' => gettype($instructor->work_days)
            ]);

            if (is_string($instructor->work_days) && !empty($instructor->work_days)) {
                if (str_contains($instructor->work_days, ',')) {
                    $instructor->work_days = array_map('trim', explode(',', $instructor->work_days));
                } else {
                    $instructor->work_days = [$instructor->work_days];
                }
            } else {
                $instructor->work_days = [];
            }
            Log::info('work_days المعالجة (سيتم تشفيرها كـ JSON بواسطة Laravel):', [
                'الاسم' => $instructor->full_name,
                'القيمة المعالجة' => $instructor->work_days,
                'النوع المعالج' => gettype($instructor->work_days)
            ]);
        });
        return response()->json($instructors);
    }

    /**
     * جلب جميع القاعات.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllRooms()
    {
        $rooms = Room::all(['id', 'room_name']);
        return response()->json($rooms);
    }

    /**
     * يحول اسم اليوم من الإنجليزية إلى العربية.
     *
     * @param string $dayName
     * @return string
     */
    private function mapDayToArabic(string $dayName): string
    {
        switch ($dayName) {
            case 'Saturday': return 'السبت';
            case 'Sunday': return 'الأحد';
            case 'Monday': return 'الاثنين';
            case 'Tuesday': return 'الثلاثاء';
            case 'Wednesday': return 'الأربعاء';
            case 'Thursday': return 'الخميس';
            case 'Friday': return 'الجمعة';
            default: return $dayName;
        }
    }












    public function doctor()
    {
        // هنا نقوم بجلب الدكاترة فقط.
        // افترض أن لديك عمود 'position' في جدول instructors يميز بين 'Professor' و 'Assistant'
        $doctors = Instructor::where('position', 'Professor')->get();

        // تمرير الدكاترة إلى الـ View
        return view('Tables.Doctor_tables', ['Doc' => $doctors]);
    }

    public function assistant()
    {
        // هنا نقوم بجلب الدكاترة فقط.
        // افترض أن لديك عمود 'position' في جدول instructors يميز بين 'Professor' و 'Assistant'
        $doctors = Instructor::where('position', 'Assistant')->get();

        // تمرير الدكاترة إلى الـ View
        return view('Tables.Doctor_tables', ['Doc' => $doctors]);
    }

    public function instructorTable($id)
    {
        // جلب بيانات الدكتور/المعيد
        $instructor = Instructor::findOrFail($id);

        // جلب جميع إدخالات الجدول الزمني الخاصة بهذا الدكتور/المعيد
        // مع جلب العلاقات الضرورية (المادة، القاعة، القسم)
        $timetables = Timetable::where('professor_id', $id)
                               ->orWhere('assistant_id', $id)
                               ->with(['course', 'room', 'department'])
                               ->orderBy('year')
                               ->orderBy('department_id')
                               ->orderBy('day')
                               ->orderBy('period_number')
                               ->get();

        // هيكلة البيانات لتكون مقسمة حسب السنة والقسم
        $formattedTimetable = [];
        $daysOfWeek = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
        $periodsMap = [1 => '9:00-11:00', 2 => '11:00-1:00', 3 => '1:00-3:00', 4 => '3:00-5:00', 5 => '5:00-7:00'];

        foreach ($timetables as $entry) {
            $year = $entry->year;
            $departmentName = $entry->department ? $entry->department->department_name : 'Unknown Department';
            $day = $entry->day;
            $periodNumber = $entry->period_number;

            // إذا كانت السنة أو القسم غير موجودين في الهيكل، قم بتهيئتهما
            if (!isset($formattedTimetable[$year])) {
                $formattedTimetable[$year] = [];
            }
            if (!isset($formattedTimetable[$year][$departmentName])) {
                $formattedTimetable[$year][$departmentName] = [];
                // تهيئة الجدول لكل قسم/سنة مع الأيام والفترات كـ 'empty'
                foreach ($daysOfWeek as $d) {
                    foreach (array_keys($periodsMap) as $p) {
                        $formattedTimetable[$year][$departmentName][$d][$p] = [
                            'course' => null,
                            'room' => null,
                            'instructor_type' => null
                        ];
                    }
                }
            }

            // ملء الخانة بالبيانات الفعلية
            $instructorType = $entry->professor_id == $id ? 'Professor' : 'Assistant';

            $formattedTimetable[$year][$departmentName][$day][$periodNumber] = [
                'course' => $entry->course ? $entry->course->course_name : 'N/A',
                'room' => $entry->room ? $entry->room->room_name : 'N/A',
                'instructor_type' => $instructorType
            ];
        }

        // تمرير البيانات إلى الـ View الجديد
        return view('Tables.instructor_individual_timetable', compact(
            'instructor',
            'formattedTimetable',
            'daysOfWeek',
            'periodsMap'
        ));
    }

    /**
     * يحول اسم اليوم من الإنجليزية إلى العربية.
     *
     * @param string $dayName
     * @return string
     */
    private function translateDayToArabic(string $dayName): string // تم تغيير الاسم هنا
    {
        switch ($dayName) {
            case 'Saturday': return 'السبت';
            case 'Sunday': return 'الأحد';
            case 'Monday': return 'الاثنين';
            case 'Tuesday': return 'الثلاثاء';
            case 'Wednesday': return 'الأربعاء';
            case 'Thursday': return 'الخميس';
            case 'Friday': return 'الجمعة';
            default: return $dayName;
        }
    }

    public function classroom()
    {
        // جلب جميع القاعات
        $rooms = Room::orderBy('room_name')->get();

        // الأيام التي ستعرض في الجدول (السبت إلى الخميس)
        $daysOfWeek = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];

        // الفترات الزمنية
        $periodsMap = [
            1 => '9:00-11:00',
            2 => '11:00-1:00',
            3 => '1:00-3:00',
            4 => '3:00-5:00',
            5 => '5:00-7:00'
        ];

        // جلب جميع إدخالات الجدول الزمني المطلوبة
        // مع جلب العلاقات الضرورية (المادة، الأستاذ، القسم)
        $allTimetables = Timetable::with(['course', 'professor', 'assistant', 'department'])
                                  ->whereIn('day', $daysOfWeek)
                                  ->orderBy('room_id')
                                  ->orderBy('day')
                                  ->orderBy('period_number')
                                  ->get();

        // هيكلة البيانات لتكون مناسبة للعرض في الجدول
        $formattedRoomTimetable = [];

        foreach ($rooms as $room) {
            $formattedRoomTimetable[$room->id] = [
                'name' => $room->room_name,
                'schedule' => []
            ];

            // تهيئة الجدول لكل قاعة مع الأيام والفترات كـ 'available'
            foreach ($daysOfWeek as $day) {
                // كل يوم سيكون عبارة عن مصفوفة من الفترات المشغولة
                $formattedRoomTimetable[$room->id]['schedule'][$day] = []; // ستخزن الفترات المشغولة فقط
            }
        }

        // ملء البيانات من Timetable entries
        foreach ($allTimetables as $entry) {
            $roomId = $entry->room_id;
            $day = $entry->day;
            $periodNumber = $entry->period_number;

            // إذا كانت القاعة واليوم موجودين في الهيكل
            if (isset($formattedRoomTimetable[$roomId]) && isset($formattedRoomTimetable[$roomId]['schedule'][$day])) {
                // أضف تفاصيل الحصة إلى مصفوفة الفترات المشغولة لهذا اليوم وهذه القاعة
                $details = '';
                if ($entry->course) {
                    $details .= $entry->course->course_name;
                }
                if ($entry->professor) {
                    $details .= ' (' . $entry->professor->full_name . ')';
                } elseif ($entry->assistant) {
                    $details .= ' (' . $entry->assistant->full_name . ')';
                }
                if ($entry->department) {
                    $details .= ' - ' . $entry->department->department_name . ' - Year ' . $entry->year;
                }

                $formattedRoomTimetable[$roomId]['schedule'][$day][$periodNumber] = [
                    'period_time' => $periodsMap[$periodNumber],
                    'details' => $details
                ];
            }
        }

        // تمرير البيانات إلى الـ View الجديد
        return view('Tables.Rooms_table', compact(
            'rooms',
            'daysOfWeek',
            'periodsMap',
            'formattedRoomTimetable'
        ));
    }


}
