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
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;

Route::get('/', function () {
    return view('login');
});


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/department',[DepartmentController::class, 'index'])->name('department.index');
Route::get('/overview/[password]', function () {
    $students= Student::count();
    $courses= Course::count();
    $instructors= Instructor::count();
    return view('home',compact('students','courses', 'instructors'));
})->name('overview');

Route::get('/overview/instructor/', [InstructorController::class, 'index'])->name('instructor');
Route::get('/overview/instructor/is', [InstructorController::class, 'isIndex'])->name('instructor.is');
Route::get('/overview/instructor/cs', [InstructorController::class, 'csIndex'])->name('instructor.cs');
Route::get('/overview/instructor/ai', [InstructorController::class, 'aiIndex'])->name('instructor.ai');
Route::get('/overview/instructor/bio', [InstructorController::class, 'bioIndex'])->name('instructor.bio');
Route::get('/overview/course', [CourseController::class, 'index'])->name('courses.index');
Route::get('/overview/course/semester', [CourseController::class, 'semester1'])->name('courses.semester1');
Route::get('/overview/course/semester/2', [CourseController::class, 'semester2'])->name('courses.semester2');
Route::get('/overview/course/semester/level1', [CourseController::class, 'semester1level1'])->name('courses.semester1level1');
Route::get('/overview/course/semester/level2', [CourseController::class, 'semester1level2'])->name('courses.semester1level2');
Route::get('/overview/course/semester/level3', [CourseController::class, 'semester1level3'])->name('courses.semester1level3');
Route::get('/overview/course/semester/level4', [CourseController::class, 'semester1level4'])->name('courses.semester1level4');
Route::get('/overview/course/semester/2/level1', [CourseController::class, 'semester2level1'])->name('courses.semester2level1');
Route::get('/overview/course/semester/2/level2', [CourseController::class, 'semester2level2'])->name('courses.semester2level2');
Route::get('/overview/course/semester/2/level3', [CourseController::class, 'semester2level3'])->name('courses.semester2level3');
Route::get('/overview/course/semester/2/level4', [CourseController::class, 'semester2level4'])->name('courses.semester2level4');
Route::get('/overview/student/', [StudentController::class, 'index'])->name('student.index');
Route::get('/overview/student/lev1_student', [StudentController::class, 'lev1_student'])->name('student.lev1_student');
Route::get('/overview/student/lev2_student', [StudentController::class, 'lev2_student'])->name('student.lev2_student');
Route::get('/overview/student/lev3_student', [StudentController::class, 'lev3_student'])->name('student.lev3_student');
Route::get('/overview/student/lev4_student', [StudentController::class, 'lev4_student'])->name('student.lev4_student');



Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/overview/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/overview/financial', [FinancialController::class, 'financial'])->name('financial.financial');
Route::get('/overview/event', [EventController::class, 'index'])->name('event.index');
Route::get('/events', [EventController::class, 'fetchEvents']);
Route::get('/financial-reports', [FinancialController::class, 'index']);
Route::get('/overview/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('/overview/attendance/l1', [AttendanceController::class, 'attendancel1'])->name('attendance.l1');
Route::get('/overview/attendance/l2', [AttendanceController::class, 'attendancel2'])->name('attendance.l2');
Route::get('/overview/attendance/l3', [AttendanceController::class, 'attendancel3'])->name('attendance.l3');
Route::get('/overview/attendance/l4', [AttendanceController::class, 'attendancel4'])->name('attendance.l4');


Route::get('/attendance', [AttendanceController::class, 'getAttendance']);
Route::get('/attendanceLev2', [AttendanceController::class, 'getAttendanceLev2']);
Route::get('/attendanceLev3', [AttendanceController::class, 'getAttendanceLev3']);
Route::get('/attendanceLev4', [AttendanceController::class, 'getAttendanceLev4']);



Route::get('/attendanceRate', [AttendanceController::class, 'getAttendanceRate']);




Route::get('/table', [CourseController::class, 'table'])->name('tables');
Route::get('/table/assistant', [TableController::class, 'assistant'])->name('assistant');
Route::get('/table/doctor', [TableController::class, 'doctor'])->name('doctor');
Route::get('/table/room', [TableController::class, 'room'])->name('room');
Route::get('/table/timeTable', [TableController::class, 'timeTable'])->name('timeTable');
Route::get('/table/doctor/docName', [TableController::class, 'docName'])->name('docName');
Route::get('/table/doctor/classroom', [TableController::class, 'classroom'])->name('classroom');
Route::get('/table/doctor/timetable/l1', [TableController::class, 'l1'])->name('l1');




// Route::post('/send-reset-code', [ForgotPasswordController::class, 'sendResetCode']);
// Route::post('/verify-reset-code', [ForgotPasswordController::class, 'verifyResetCode']);
// Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);
// Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot.password');
// Route::get('/reset-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password');




Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot.password');
Route::post('/send-reset-code', [ForgotPasswordController::class, 'sendResetCode'])->name('send.reset.code');


Route::get('/verify-reset-code', [ForgotPasswordController::class, 'showVerifyResetCodeForm'])->name('verify.code.form');
Route::post('/verify-reset-code', [ForgotPasswordController::class, 'verifyResetCode'])->name('password.verifyResetCode');

Route::get('/reset-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');










// use Illuminate\Support\Facades\Mail;

// Route::get('/test-email', function () {
//     Mail::raw('This is a test email', function ($message) {
//         $message->to('ba9329711@gmail.com')->subject('Test Email');

//     });

//     return 'Test email sent successfully!';
// });



Route::get('/table/tt/ss', function () {
    return view('tables');
});

// Route::get('/test', function () {
//     $user = new User();
//     $user->name = 'Abdelfattah Mohammed';
//     $user->email = 'arg24680@gmail.com';
//     $user->password = bcrypt('12345678');
//     $user->save();
// });
