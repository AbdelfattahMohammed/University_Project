<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return view('course');
    }

    // public function semester1()
    // {
    //     return view('index');
    // }

    // public function semester2()
    // {
    //     return view('levels sem2');
    // }

    public function semester1level1()
    {
        $courses = DB::table('course_department')
            ->join('courses', 'course_department.course_id', '=', 'courses.id')
            ->select('courses.*', 'course_department.department_id') // جلب course.* و department_id من جدول الربط
            ->where('course_department.year', 1) // year في جدول course_department يمثل المستوى/السنة
            ->where('courses.term', 1) // term في جدول courses يمثل الفصل الدراسي
            ->get()
            ->groupBy('department_id');

        // ملاحظة: مع groupBy، لن تستطيع استخدام with('instructors') مباشرة على Collection.
        // ستحتاج إلى جلب Instructors لكل مقرر يدويًا داخل الـ view أو إعادة هيكلة الجلب.
        // إذا كنت تحتاج علاقة instructors، يجب جلبها قبل الـ groupBy.
        // الطريقة الأكثر فعالية هي تحميلها بعد التجميع أو استخدام "eager loading" للـ `courses` أولاً.

        // الطريقة البديلة لجلب المقررات مع Instructors قبل التجميع
        $rawCourses = Course::whereHas('departments', function ($query) {
                $query->where('year', 1);
            })
            ->where('term', 1)
            ->with('instructors', 'departments') // حمل علاقة الأقسام أيضًا للحصول على department_id
            ->get();

        // الآن قم بالتجميع بناءً على department_id من العلاقة
        $coursesGroupedByDepartment = $rawCourses->flatMap(function ($course) {
            return $course->departments->map(function ($department) use ($course) {
                // إنشاء نسخة من المقرر مع department_id محدد
                $c = clone $course;
                $c->department_id = $department->id;
                return $c;
            });
        })->groupBy('department_id');

        // يمكنك تمرير $coursesGroupedByDepartment إلى الـ view
        // إذا كان الـ view يعتمد على $courses التي هي Collection من Courses، فهذه هي الطريقة الصحيحة.
        return view('level1', ['courses' => $coursesGroupedByDepartment]);
    }

    /**
     * جلب المقررات للفصل الدراسي الأول، المستوى الثاني (السنة الثانية).
     *
     * @return \Illuminate\View\View
     */
    public function semester1level2()
    {
        $rawCourses = Course::whereHas('departments', function ($query) {
                $query->where('year', 2);
            })
            ->where('term', 1)
            ->with('instructors', 'departments')
            ->get();

        $coursesGroupedByDepartment = $rawCourses->flatMap(function ($course) {
            return $course->departments->map(function ($department) use ($course) {
                $c = clone $course;
                $c->department_id = $department->id;
                return $c;
            });
        })->groupBy('department_id');

        return view('level2', ['courses' => $coursesGroupedByDepartment]);
    }

    /**
     * جلب المقررات للفصل الدراسي الأول، المستوى الثالث (السنة الثالثة).
     *
     * @return \Illuminate\View\View
     */
    public function semester1level3()
    {
        $rawCourses = Course::whereHas('departments', function ($query) {
                $query->where('year', 3);
            })
            ->where('term', 1)
            ->with('instructors', 'departments')
            ->get();

        $coursesGroupedByDepartment = $rawCourses->flatMap(function ($course) {
            return $course->departments->map(function ($department) use ($course) {
                $c = clone $course;
                $c->department_id = $department->id;
                return $c;
            });
        })->groupBy('department_id');

        return view('level3', ['courses' => $coursesGroupedByDepartment]);
    }

    /**
     * جلب المقررات للفصل الدراسي الأول، المستوى الرابع (السنة الرابعة).
     *
     * @return \Illuminate\View\View
     */
    public function semester1level4()
    {
        $rawCourses = Course::whereHas('departments', function ($query) {
                $query->where('year', 4);
            })
            ->where('term', 1)
            ->with('instructors', 'departments')
            ->get();

        $coursesGroupedByDepartment = $rawCourses->flatMap(function ($course) {
            return $course->departments->map(function ($department) use ($course) {
                $c = clone $course;
                $c->department_id = $department->id;
                return $c;
            });
        })->groupBy('department_id');

        return view('level4', ['courses' => $coursesGroupedByDepartment]);
    }

    /**
     * جلب المقررات للفصل الدراسي الثاني، المستوى الأول (السنة الأولى).
     *
     * @return \Illuminate\View\View
     */
    public function semester2level1()
    {
        $rawCourses = Course::whereHas('departments', function ($query) {
                $query->where('year', 1);
            })
            ->where('term', 2)
            ->with('instructors', 'departments')
            ->get();

        $coursesGroupedByDepartment = $rawCourses->flatMap(function ($course) {
            return $course->departments->map(function ($department) use ($course) {
                $c = clone $course;
                $c->department_id = $department->id;
                return $c;
            });
        })->groupBy('department_id');

        return view('semster2l1', ['courses' => $coursesGroupedByDepartment]);
    }

    /**
     * جلب المقررات للفصل الدراسي الثاني، المستوى الثاني (السنة الثانية).
     *
     * @return \Illuminate\View\View
     */
    public function semester2level2()
    {
        $rawCourses = Course::whereHas('departments', function ($query) {
                $query->where('year', 2);
            })
            ->where('term', 2)
            ->with('instructors', 'departments')
            ->get();

        $coursesGroupedByDepartment = $rawCourses->flatMap(function ($course) {
            return $course->departments->map(function ($department) use ($course) {
                $c = clone $course;
                $c->department_id = $department->id;
                return $c;
            });
        })->groupBy('department_id');

        return view('semster2l2', ['courses' => $coursesGroupedByDepartment]);
    }

    /**
     * جلب المقررات للفصل الدراسي الثاني، المستوى الثالث (السنة الثالثة).
     *
     * @return \Illuminate\View\View
     */
    public function semester2level3()
    {
        $rawCourses = Course::whereHas('departments', function ($query) {
                $query->where('year', 3);
            })
            ->where('term', 2)
            ->with('instructors', 'departments')
            ->get();

        $coursesGroupedByDepartment = $rawCourses->flatMap(function ($course) {
            return $course->departments->map(function ($department) use ($course) {
                $c = clone $course;
                $c->department_id = $department->id;
                return $c;
            });
        })->groupBy('department_id');

        return view('semster2l3', ['courses' => $coursesGroupedByDepartment]);
    }

    /**
     * جلب المقررات للفصل الدراسي الثاني، المستوى الرابع (السنة الرابعة).
     *
     * @return \Illuminate\View\View
     */
    public function semester2level4()
    {
        $rawCourses = Course::whereHas('departments', function ($query) {
                $query->where('year', 4);
            })
            ->where('term', 2)
            ->with('instructors', 'departments')
            ->get();

        $coursesGroupedByDepartment = $rawCourses->flatMap(function ($course) {
            return $course->departments->map(function ($department) use ($course) {
                $c = clone $course;
                $c->department_id = $department->id;
                return $c;
            });
        })->groupBy('department_id');

        return view('semster2l4', ['courses' => $coursesGroupedByDepartment]);
    }
    public function table()
    {
        return view('Tables.tables');
    }
}
