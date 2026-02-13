<?php

use App\Http\Controllers\Accounting\InvoiceController;
use App\Http\Controllers\Accounting\ReceiptController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DossierEleveController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\PedagogyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RhDocumentController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ToolsMessagingController;
use App\Http\Controllers\ToolsAuditController;
use App\Models\ModePaiement;
use App\Models\ParametreEcole;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{id}', [StudentController::class, 'show'])
        ->whereNumber('id')
        ->name('students.show');
    Route::get('/students/{id}/registration-pdf', [StudentController::class, 'registrationPdf'])
        ->whereNumber('id')
        ->name('students.registration.pdf');
    Route::get('/students/enrollments', [EnrollmentController::class, 'hub'])
        ->name('students.enrollments');
    Route::get('/students/enrollments/create', [EnrollmentController::class, 'create'])
        ->name('students.enrollments.create');
    Route::post('/students/enrollments', [EnrollmentController::class, 'store'])
        ->name('students.enrollments.store');
    Route::get('/students/re-enrollments', [EnrollmentController::class, 'reEnrollments'])
        ->name('students.re-enrollments');
    Route::post('/students/re-enrollments', [EnrollmentController::class, 'storeReEnrollment'])
        ->name('students.re-enrollments.store');

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
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{id}', [StaffController::class, 'show'])->name('staff.show');
    Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');

    Route::get('/rh/documents', [RhDocumentController::class, 'index'])->name('rh.documents.index');
    Route::post('/rh/documents', [RhDocumentController::class, 'store'])->name('rh.documents.store');
    Route::put('/rh/documents/{document}', [RhDocumentController::class, 'update'])->name('rh.documents.update');
    Route::delete('/rh/documents/{document}', [RhDocumentController::class, 'destroy'])->name('rh.documents.destroy');
    Route::get('/rh/documents/{document}/download', [RhDocumentController::class, 'download'])->name('rh.documents.download');

    Route::get('/teachers', [EnseignantController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/create', [EnseignantController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [EnseignantController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{enseignant}', [EnseignantController::class, 'show'])->name('teachers.show');
    Route::get('/teachers/{enseignant}/edit', [EnseignantController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{enseignant}', [EnseignantController::class, 'update'])->name('teachers.update');
    Route::put('/teachers/{enseignant}/archive', [EnseignantController::class, 'archive'])->name('teachers.archive');
    Route::delete('/teachers/{enseignant}', [EnseignantController::class, 'destroy'])->name('teachers.destroy');
    Route::post('/teachers/{enseignant}/documents', [EnseignantController::class, 'storeDocument'])
        ->name('teachers.documents.store');
    Route::delete('/teachers/{enseignant}/documents/{document}', [EnseignantController::class, 'destroyDocument'])
        ->name('teachers.documents.destroy');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/academic-years', [SettingsController::class, 'storeAcademicYear'])
        ->name('settings.academic-years.store');
    Route::post('/settings/academic-years/{academicYear}/status', [SettingsController::class, 'updateAcademicYearStatus'])
        ->name('settings.academic-years.status');
    Route::post('/settings/academic-years/{academicYear}/terms', [SettingsController::class, 'storeTerms'])
        ->name('settings.academic-years.terms.store');
    Route::post('/settings/periods', [SettingsController::class, 'storePeriods'])
        ->name('settings.periods.store');
    Route::post('/settings/fees', [SettingsController::class, 'storeFee'])->name('settings.fees.store');
    Route::post('/settings/levels', [SettingsController::class, 'storeLevel'])->name('settings.levels.store');
    Route::put('/settings/levels/{level}', [SettingsController::class, 'updateLevel'])->name('settings.levels.update');
    Route::post('/settings/levels/{level}/status', [SettingsController::class, 'updateLevelStatus'])
        ->name('settings.levels.status');
    Route::post('/settings/series', [SettingsController::class, 'storeSerie'])->name('settings.series.store');
    Route::put('/settings/series/{serie}', [SettingsController::class, 'updateSerie'])->name('settings.series.update');
    Route::post('/settings/series/{serie}/status', [SettingsController::class, 'updateSerieStatus'])
        ->name('settings.series.status');
    Route::post('/settings/subjects', [SettingsController::class, 'storeSubject'])->name('settings.subjects.store');
    Route::put('/settings/subjects/{subject}', [SettingsController::class, 'updateSubject'])->name('settings.subjects.update');
    Route::post('/settings/subjects/{subject}/status', [SettingsController::class, 'updateSubjectStatus'])
        ->name('settings.subjects.status');
    Route::post('/settings/official-coefficients', [SettingsController::class, 'storeOfficialCoefficients'])
        ->name('settings.coefficients.store');
    Route::post('/settings/official-coefficients/defaults', [SettingsController::class, 'applyDefaultOfficialCoefficients'])
        ->name('settings.coefficients.defaults');
    Route::post('/settings/official-coefficients/copy', [SettingsController::class, 'copyOfficialCoefficients'])
        ->name('settings.coefficients.copy');
    Route::post('/settings/documents', [SettingsController::class, 'updateDocuments'])->name('settings.documents.update');

    Route::get('/tools/dossiers-eleves', [DossierEleveController::class, 'index'])
        ->name('tools.student-files.index');

    Route::prefix('/tools/messaging')->name('tools.messaging.')->group(function () {
        Route::get('/', [ToolsMessagingController::class, 'index'])->name('index');
        Route::get('/create', [ToolsMessagingController::class, 'create'])->name('create');
        Route::post('/', [ToolsMessagingController::class, 'store'])->name('store');
        Route::get('/conversations/{conversation}', [ToolsMessagingController::class, 'show'])->name('show');
    });

    Route::prefix('/tools/audit')->name('tools.audit.')->group(function () {
        Route::get('/', [ToolsAuditController::class, 'index'])->name('index');
        Route::get('/actions/{action}', [ToolsAuditController::class, 'showAction'])->name('actions.show');
        Route::get('/connections/{connection}', [ToolsAuditController::class, 'showConnection'])->name('connections.show');
    });

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
            Route::get('/', [InvoiceController::class, 'index'])->name('index');
            Route::get('/student', fn () => view('accounting.invoices.student'))->name('student');
            Route::get('/class', fn () => view('accounting.invoices.class'))->name('class');
            Route::get('/unpaid', [InvoiceController::class, 'unpaid'])->name('unpaid');
        });

        Route::prefix('receipts')->name('receipts.')->group(function () {
            Route::get('/', [ReceiptController::class, 'index'])->name('list');
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
            Route::get('/payment-modes', fn () => view('accounting.settings.payment-modes', [
                'paymentModes' => ModePaiement::query()->orderBy('libelle')->get(),
            ]))
                ->name('payment-modes');
            Route::get('/custom-fields', fn () => view('accounting.settings.custom-fields'))
                ->name('custom-fields');
            Route::get('/templates', fn () => view('accounting.settings.templates', [
                'schoolSettings' => ParametreEcole::query()->first(),
            ]))
                ->name('templates');
        });
    });

    Route::prefix('pedagogy')->name('pedagogy.')->group(function () {
        Route::get('/subjects', [PedagogyController::class, 'subjects'])->name('subjects.index');
        Route::patch('/subjects/{subject}/status', [PedagogyController::class, 'updateSubjectStatus'])
            ->name('subjects.status');

        Route::get('/programme', [PedagogyController::class, 'programme'])->name('programme.index');
        Route::post('/programme', [PedagogyController::class, 'storeProgrammeSubject'])->name('programme.store');
        Route::delete('/programme/{programme}', [PedagogyController::class, 'destroyProgrammeSubject'])
            ->name('programme.destroy');

        Route::get('/assignments', [PedagogyController::class, 'assignments'])->name('assignments.index');
        Route::post('/assignments', [PedagogyController::class, 'storeAssignment'])->name('assignments.store');

        Route::get('/evaluations', [PedagogyController::class, 'evaluations'])->name('evaluations.index');
        Route::post('/evaluations', [PedagogyController::class, 'storeEvaluation'])->name('evaluations.store');
        Route::patch('/evaluations/{evaluation}', [PedagogyController::class, 'updateEvaluation'])->name('evaluations.update');
        Route::post('/evaluations/{evaluation}/status', [PedagogyController::class, 'updateEvaluationStatus'])
            ->name('evaluations.status');
        Route::delete('/evaluations/{evaluation}', [PedagogyController::class, 'destroyEvaluation'])
            ->name('evaluations.destroy');

        Route::get('/grades', [PedagogyController::class, 'grades'])->name('grades.index');
        Route::post('/grades/{evaluation}', [PedagogyController::class, 'storeGrades'])->name('grades.store');

        Route::get('/report-cards', [PedagogyController::class, 'reportCards'])->name('report-cards.index');
        Route::post('/report-cards/lock', [PedagogyController::class, 'toggleReportLock'])
            ->name('report-cards.lock');
        Route::get('/report-cards/{class}/{period}/pdf', [PedagogyController::class, 'reportCardsPdf'])
            ->name('report-cards.pdf');
        Route::post('/report-cards/{class}/{period}/email', [PedagogyController::class, 'reportCardsEmail'])
            ->name('report-cards.email');

        Route::get('/transcripts', [PedagogyController::class, 'transcripts'])->name('transcripts.index');
        Route::get('/transcripts/{student}/pdf', [PedagogyController::class, 'transcriptPdf'])
            ->name('transcripts.pdf');

        Route::get('/leaderboard', [PedagogyController::class, 'leaderboard'])->name('leaderboard.index');
        Route::get('/dashboard', [PedagogyController::class, 'dashboard'])->name('dashboard.index');
        Route::get('/results-dashboard', [PedagogyController::class, 'resultsDashboard'])->name('results-dashboard.index');
        Route::get('/student-report-cards', [PedagogyController::class, 'studentReportCards'])
            ->name('student-report-cards.index');
    });
});

require __DIR__.'/auth.php';
