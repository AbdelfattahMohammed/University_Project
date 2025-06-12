<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\InstructorController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TimetableController;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Room;
use App\Models\Student;
use App\Models\User; // لو بتستخدمها في أي مكان

// -------------------------------------------------------------
// ROUTES غير المحمية (لا تحتاج تسجيل دخول)
// -------------------------------------------------------------

// صفحة تسجيل الدخول هي الوحيدة اللي لازم تكون متاحة بدون تسجيل دخول
Route::get('/', function () {
    return view('login');
});


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// مسارات استعادة كلمة المرور أيضاً يجب أن تكون متاحة بدون تسجيل دخول
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot.password');
Route::post('/send-reset-code', [ForgotPasswordController::class, 'sendResetCode'])->name('send.reset.code');
Route::get('/verify-reset-code', [ForgotPasswordController::class, 'showVerifyResetCodeForm'])->name('verify.code.form');
Route::post('/verify-reset-code', [ForgotPasswordController::class, 'verifyResetCode'])->name('password.verifyResetCode');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');

// -------------------------------------------------------------
// ROUTES المحمية (تحتاج تسجيل دخول)
// -------------------------------------------------------------
// كل المسارات اللي تحت دي هيحتاج المستخدم يكون عامل تسجيل دخول عشان يوصلها
Route::middleware('auth')->group(function () {
    // مسار تسجيل الخروج (لازم يكون داخل auth عشان ميتعملش logout الا لو عامل login)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Overview Page (الـ password في الـ URL هنا مش مستحب ومش آمن، يفضل إزالته)
    // لو الـ overview محتاجة بيانات محددة، الأفضل تجيبها من الـ Controller
    Route::get('/overview', function () { // تم تغيير /overview/[password] إلى /overview فقط
        $students = Student::count();
        $courses = Course::count();
        $instructors = Instructor::count();
        $rooms = Room::count();
        return view('home', compact('students', 'courses', 'instructors', 'rooms'));
    })->name('overview');


    // Department Routes
    Route::get('/department', [DepartmentController::class, 'index'])->name('department.index');

    // Instructor Routes
    Route::get('/overview/instructor/', [InstructorController::class, 'index'])->name('instructor');
    Route::get('/overview/instructor/is', [InstructorController::class, 'isIndex'])->name('instructor.is');
    Route::get('/overview/instructor/cs', [InstructorController::class, 'csIndex'])->name('instructor.cs');
    Route::get('/overview/instructor/ai', [InstructorController::class, 'aiIndex'])->name('instructor.ai');
    Route::get('/overview/instructor/bio', [InstructorController::class, 'bioIndex'])->name('instructor.bio');

    // Course Routes
    Route::get('/overview/course', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/overview/course/semester/level1', [CourseController::class, 'semester1level1'])->name('courses.semester1level1');
    Route::get('/overview/course/semester/2/level1', [CourseController::class, 'semester2level1'])->name('courses.semester2level1');
    Route::get('/overview/course/semester/level2', [CourseController::class, 'semester1level2'])->name('courses.semester1level2');
    Route::get('/overview/course/semester/2/level2', [CourseController::class, 'semester2level2'])->name('courses.semester2level2');
    Route::get('/overview/course/semester/level3', [CourseController::class, 'semester1level3'])->name('courses.semester1level3');
    Route::get('/overview/course/semester/2/level3', [CourseController::class, 'semester2level3'])->name('courses.semester2level3');
    Route::get('/overview/course/semester/level4', [CourseController::class, 'semester1level4'])->name('courses.semester1level4');
    Route::get('/overview/course/semester/2/level4', [CourseController::class, 'semester2level4'])->name('courses.semester2level4');

    // Student Routes
    Route::get('/overview/student/', [StudentController::class, 'index'])->name('student.index');
    Route::get('/overview/student/lev1_student', [StudentController::class, 'lev1_student'])->name('student.lev1_student');
    Route::get('/overview/student/lev2_student', [StudentController::class, 'lev2_student'])->name('student.lev2_student');
    Route::get('/overview/student/lev3_student', [StudentController::class, 'lev3_student'])->name('student.lev3_student');
    Route::get('/overview/student/lev4_student', [StudentController::class, 'lev4_student'])->name('student.lev4_student');

    // Search Route
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Attendance Routes
    Route::get('/overview/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance-by-year/{year}', [AttendanceController::class, 'getAttendanceByYear']);
    Route::get('/attendance-rate', [AttendanceController::class, 'getAttendanceRate']);

    // Financial Routes
    Route::get('/overview/financial', [FinancialController::class, 'financial'])->name('financial.financial');
    Route::get('/financial-reports', [FinancialController::class, 'index']); // (مكرر في الكود الأصلي، تأكد من حذف الزيادات)

    // Event Routes
    Route::get('/overview/event', [EventController::class, 'index'])->name('event.index');
    Route::get('/events', [EventController::class, 'fetchEvents']);

    // Timetable/Table Routes
    Route::get('/table', [CourseController::class, 'table'])->name('tables'); // المسار ده مش واضح ليه في CourseController
    Route::get('/table/assistant', [TableController::class, 'assistant'])->name('assistant');
    Route::get('/table/doctor', [TableController::class, 'doctor'])->name('doctor');
    Route::get('/table/create_table', [TableController::class, 'create_table'])->name('create_table');
    Route::get('/table/doctor/docName/{id}', [TableController::class, 'instructorTable'])->name('instructorTable');
    Route::get('/table/doctor/classroom', [TableController::class, 'classroom'])->name('classroom');

    Route::get('/table/tt/ss', function () { // هذا المسار يعرض view('tables')
        return view('tables');
    });

    Route::get('/timetable/index', [TableController::class, 'index'])->name('timetable.index');

    // Timetables Management Routes
    Route::get('/timetables/select-year', [TimetableController::class, 'showYearSelection'])->name('timetables.select_year');
    Route::get('/timetables/year/{year}', [TimetableController::class, 'showDepartmentTimetablesForYear'])->name('timetables.year_overview');
    Route::get('/timetables/manage/{year}/{department_id}', [TimetableController::class, 'manageTimetableGrid'])->name('timetables.manage_grid');

    // Test Routes (لو محتاجهم للتجربة بس، يفضل تمسحهم في الـ production)
    // Route::get('/test', function () {
    //     $user = new User();
    //     $user->name = 'Abdelfattah Mohammed';
    //     $user->email = 'arg24680@gmail.com';
    //     $user->password = bcrypt('12345678');
    //     $user->save();
    // });
    // Route::get('/test-email', function () {
    //     Mail::raw('This is a test email', function ($message) {
    //         $message->to('ba9329711@gmail.com')->subject('Test Email');
    //     });
    //     return 'Test email sent successfully!';
    // });
});
