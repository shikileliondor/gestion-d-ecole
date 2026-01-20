<?php

namespace App\Http\Controllers;

use App\Http\Requests\Classes\AssignClassSubjectRequest;
use App\Http\Requests\Classes\AssignStudentClassRequest;
use App\Http\Requests\Classes\StoreClassRequest;
use App\Http\Requests\Classes\StoreSubjectRequest;
use App\Http\Requests\Classes\UpdateClassHeadcountRequest;
use App\Models\AcademicYear;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Subject;
use App\Services\ClassService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolClassController extends Controller
{
    public function index(): View
    {
        $schoolId = School::query()->value('id');

        $classes = SchoolClass::query()
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->with('academicYear')
            ->withCount(['studentAssignments', 'subjectAssignments'])
            ->orderBy('name')
            ->get();

        $academicYears = AcademicYear::query()
            ->orderByDesc('start_date')
            ->get();

        $subjects = Subject::query()
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->orderBy('name')
            ->get();

        $students = Student::query()
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $staff = Staff::query()
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('classes.index', compact('classes', 'academicYears', 'subjects', 'students', 'staff'));
    }

    public function store(StoreClassRequest $request, ClassService $service): RedirectResponse
    {
        $service->createClass($request->validated());

        return back()->with('status', 'La classe a été créée avec succès.');
    }

    public function updateHeadcount(
        UpdateClassHeadcountRequest $request,
        SchoolClass $class,
        ClassService $service
    ): RedirectResponse {
        $data = $request->validated();

        $service->updateHeadcount($class, $data['manual_headcount']);

        return back()->with('status', "L'effectif de la classe a été mis à jour.");
    }

    public function storeSubject(StoreSubjectRequest $request, ClassService $service): RedirectResponse
    {
        $service->createSubject($request->validated());

        return back()->with('status', 'La matière a été créée avec succès.');
    }

    public function assignSubject(
        AssignClassSubjectRequest $request,
        SchoolClass $class,
        ClassService $service
    ): RedirectResponse {
        $service->assignSubject($class, $request->validated());

        return back()->with('status', 'La matière a été affectée à la classe.');
    }

    public function assignStudent(
        AssignStudentClassRequest $request,
        SchoolClass $class,
        ClassService $service
    ): RedirectResponse {
        $service->assignStudent($class, $request->validated());

        return back()->with('status', "L'élève a été affecté à la classe.");
    }
}
