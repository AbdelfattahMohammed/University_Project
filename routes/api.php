<?php

use App\Http\Controllers\TableController;
use App\Http\Controllers\TimetableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// `Course $course` في تعريف الدالة سيتولى Laravel جلب المقرر تلقائياً بواسطة الـ ID
Route::get('/timetables/course-instructors/{course}', [TimetableController::class, 'getCourseInstructors']);

// مسار لحفظ أو تحديث خانة الجدول
Route::post('/timetables/save-slot', [TimetableController::class, 'saveTimetableSlot'])->name('api.timetables.save_slot');
Route::get('/timetables/course-instructors/{course_id}', [TimetableController::class, 'getCourseInstructors']);
Route::delete('/timetable/delete-slot', [TimetableController::class, 'deleteTimetableSlot'])->name('timetable.delete');



Route::get('/timetable', [TableController::class, 'getTimetableData']);
Route::post('/timetable/save', [TableController::class, 'saveTimetableSlot']);
Route::get('/course/{course}/instructors', [TableController::class, 'getCourseInstructors']);
// راوت جديد لجلب جميع القاعات
Route::get('/rooms/all', [TableController::class, 'getAllRooms']);
// **الراوت الجديد لعملية الحذف (DELETE)**
Route::delete('/timetable/{id}', [TableController::class, 'deleteTimetableSlot']); // <-- إضافة هذا الراوت



