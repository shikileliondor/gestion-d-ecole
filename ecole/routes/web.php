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
    Route::get('/students/enrollments', fn () => view('students.enrollments'))
        ->name('students.enrollments');
    Route::get('/students/re-enrollments', fn () => view('students.re-enrollments'))
        ->name('students.re-enrollments');

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

    Route::prefix('accounting')->name('accounting.')->group(function () {
        Route::get('/dashboard', fn () => view('accounting.dashboard'))->name('dashboard');

        Route::prefix('income')->name('income.')->group(function () {
            Route::get('/', fn () => view('accounting.income.index'))->name('index');
            Route::get('/create', fn () => view('accounting.income.create'))->name('create');
            Route::get('/manual', fn () => view('accounting.income.manual'))->name('manual');
            Route::get('/categories', fn () => view('accounting.income.categories'))->name('categories');
        });

        Route::prefix('expenses')->name('expenses.')->group(function () {
            Route::get('/', fn () => view('accounting.expenses.index'))->name('index');
            Route::get('/create', fn () => view('accounting.expenses.create'))->name('create');
            Route::get('/categories', fn () => view('accounting.expenses.categories'))->name('categories');
        });

        Route::prefix('billing')->name('billing.')->group(function () {
            Route::get('/generate', fn () => view('accounting.billing.generate'))->name('generate');
        });

        Route::prefix('invoices')->name('invoices.')->group(function () {
            Route::get('/', fn () => view('accounting.invoices.index'))->name('index');
            Route::get('/student', fn () => view('accounting.invoices.student'))->name('student');
            Route::get('/class', fn () => view('accounting.invoices.class'))->name('class');
            Route::get('/unpaid', fn () => view('accounting.invoices.unpaid'))->name('unpaid');
        });

        Route::prefix('receipts')->name('receipts.')->group(function () {
            Route::get('/', fn () => view('accounting.receipts.list'))->name('list');
            Route::get('/download', fn () => view('accounting.receipts.download'))->name('download');
            Route::get('/numbering', fn () => view('accounting.receipts.numbering'))->name('numbering');
        });

        Route::prefix('overdue')->name('overdue.')->group(function () {
            Route::get('/class', fn () => view('accounting.overdue.class'))->name('class');
            Route::get('/student', fn () => view('accounting.overdue.student'))->name('student');
            Route::get('/history', fn () => view('accounting.overdue.history'))->name('history');
        });

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/annual', fn () => view('accounting.reports.annual'))->name('annual');
            Route::get('/monthly', fn () => view('accounting.reports.monthly'))->name('monthly');
            Route::get('/class', fn () => view('accounting.reports.class'))->name('class');
            Route::get('/category', fn () => view('accounting.reports.category'))->name('category');
        });

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/academic-years', fn () => view('accounting.settings.academic-years'))
                ->name('academic-years');
            Route::get('/revenue-categories', fn () => view('accounting.settings.revenue-categories'))
                ->name('revenue-categories');
            Route::get('/expense-categories', fn () => view('accounting.settings.expense-categories'))
                ->name('expense-categories');
            Route::get('/payment-modes', fn () => view('accounting.settings.payment-modes'))
                ->name('payment-modes');
            Route::get('/custom-fields', fn () => view('accounting.settings.custom-fields'))
                ->name('custom-fields');
            Route::get('/templates', fn () => view('accounting.settings.templates'))
                ->name('templates');
        });
    });
});

require __DIR__.'/auth.php';
