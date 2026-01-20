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
use App\Models\Setting;
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
            ->with(['subjectAssignments.subject', 'subjectAssignments.teacher', 'subjectAssignments.teachers'])
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

        $seriesSetting = Setting::query()
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->where('group', 'classes')
            ->where('key', 'series')
            ->value('value');

        $seriesOptions = $seriesSetting ? json_decode($seriesSetting, true) : [];
        $seriesOptions = is_array($seriesOptions) ? $seriesOptions : [];

        return view('classes.index', compact('classes', 'academicYears', 'subjects', 'students', 'staff', 'seriesOptions'));
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

    public function storeSeries(\Illuminate\Http\Request $request): RedirectResponse
    {
        $schoolId = School::query()->value('id');

        if (! $schoolId) {
            return back()->withErrors(['school_id' => "Aucune école n'est configurée."])->withInput();
        }

        $data = $request->validate([
            'series_list' => ['nullable', 'string', 'max:255'],
        ]);

        $series = collect(explode(',', $data['series_list'] ?? ''))
            ->map(fn ($value) => trim($value))
            ->filter()
            ->unique()
            ->values()
            ->all();

        Setting::query()->updateOrCreate(
            [
                'school_id' => $schoolId,
                'group' => 'classes',
                'key' => 'series',
            ],
            [
                'value' => json_encode($series, JSON_UNESCAPED_UNICODE),
                'type' => 'json',
                'is_public' => true,
            ]
        );

        return back()->with('status', 'Les séries ont été mises à jour.');
    }
}
