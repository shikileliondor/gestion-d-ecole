<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');

    Route::get('/classes', [SchoolClassController::class, 'index'])->name('classes.index');
    Route::post('/classes', [SchoolClassController::class, 'store'])->name('classes.store');
    Route::patch('/classes/{class}/headcount', [SchoolClassController::class, 'updateHeadcount'])
        ->name('classes.headcount.update');
    Route::post('/classes/subjects', [SchoolClassController::class, 'storeSubject'])->name('classes.subjects.store');
    Route::post('/classes/{class}/subjects', [SchoolClassController::class, 'assignSubject'])
        ->name('classes.subjects.assign');
    Route::post('/classes/series', [SchoolClassController::class, 'storeSeries'])->name('classes.series.store');
    Route::post('/classes/{class}/students', [SchoolClassController::class, 'assignStudent'])
        ->name('classes.students.assign');

    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/teachers', [StaffController::class, 'teachers'])->name('teachers.index');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{id}', [StaffController::class, 'show'])->name('staff.show');
    Route::get('/staff/contracts/{id}/download', [StaffController::class, 'downloadContract'])
        ->name('staff.contracts.download');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/academic-years', [SettingsController::class, 'storeAcademicYear'])
        ->name('settings.academic-years.store');
    Route::post('/settings/academic-years/{academicYear}/status', [SettingsController::class, 'updateAcademicYearStatus'])
        ->name('settings.academic-years.status');
    Route::post('/settings/academic-years/{academicYear}/terms', [SettingsController::class, 'storeTerms'])
        ->name('settings.academic-years.terms.store');
    Route::post('/settings/fees', [SettingsController::class, 'storeFee'])->name('settings.fees.store');
});

require __DIR__.'/auth.php';
