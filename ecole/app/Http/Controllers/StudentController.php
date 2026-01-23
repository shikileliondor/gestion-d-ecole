<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\Payment;
use App\Models\ParentProfile;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentDocument;
use App\Models\StudentParent;
use App\Models\SchoolClass;
use App\Services\MatriculeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use RuntimeException;

class StudentController extends Controller
{
    public function index(): View
    {
        $students = Student::query()
            ->select('students.*')
            ->addSelect([
                'class_name' => StudentClass::query()
                    ->select('classes.name')
                    ->join('classes', 'student_classes.class_id', '=', 'classes.id')
                    ->whereColumn('student_classes.student_id', 'students.id')
                    ->latest('student_classes.assigned_at')
                    ->limit(1),
            ])
            ->orderBy('students.last_name')
            ->orderBy('students.first_name')
            ->get();

        $classes = SchoolClass::query()
            ->orderBy('name')
            ->get();

        $academicYears = AcademicYear::query()
            ->orderByDesc('start_date')
            ->get();

        return view('students.index', compact('students', 'classes', 'academicYears'));
    }

    public function create(): View
    {
        $classes = SchoolClass::query()
            ->orderBy('name')
            ->get();

        $academicYears = AcademicYear::query()
            ->orderByDesc('start_date')
            ->get();

        return view('students.create', compact('classes', 'academicYears'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date'],
            'place_of_birth' => ['nullable', 'string', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'blood_type' => ['nullable', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'enrollment_date' => ['nullable', 'date'],
            'previous_school' => ['nullable', 'string', 'max:255'],
            'needs_special_care' => ['nullable', 'boolean'],
            'medical_notes' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', 'in:active,suspended,transferred,graduated,inactive'],
            'class_id' => ['required', 'exists:classes,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'class_status' => ['nullable', 'in:active,transferred,completed'],
            'parent_first_name' => ['nullable', 'string', 'max:255'],
            'parent_last_name' => ['nullable', 'string', 'max:255'],
            'parent_gender' => ['nullable', 'in:male,female,other'],
            'parent_relationship' => ['nullable', 'string', 'max:50'],
            'parent_phone' => ['nullable', 'string', 'max:30'],
            'parent_email' => ['nullable', 'email', 'max:255'],
            'parent_address' => ['nullable', 'string', 'max:255'],
            'parent_occupation' => ['nullable', 'string', 'max:255'],
            'parent_employer' => ['nullable', 'string', 'max:255'],
            'parent_national_id' => ['nullable', 'string', 'max:100'],
            'parent_is_primary' => ['nullable', 'boolean'],
            'parent_has_custody' => ['nullable', 'boolean'],
            'parent_notes' => ['nullable', 'string'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $parentFields = [
                $request->input('parent_first_name'),
                $request->input('parent_last_name'),
                $request->input('parent_gender'),
                $request->input('parent_relationship'),
                $request->input('parent_phone'),
                $request->input('parent_email'),
                $request->input('parent_address'),
                $request->input('parent_occupation'),
                $request->input('parent_employer'),
                $request->input('parent_national_id'),
            ];

            $hasParentData = collect($parentFields)->filter(fn ($value) => filled($value))->isNotEmpty();

            if ($hasParentData) {
                if (! $request->filled('parent_first_name')) {
                    $validator->errors()->add('parent_first_name', 'Le prénom du parent est requis.');
                }

                if (! $request->filled('parent_last_name')) {
                    $validator->errors()->add('parent_last_name', 'Le nom du parent est requis.');
                }
            }

            if ($request->filled('class_id') && $request->filled('academic_year_id')) {
                $matchesYear = SchoolClass::query()
                    ->whereKey($request->input('class_id'))
                    ->where('academic_year_id', $request->input('academic_year_id'))
                    ->exists();

                if (! $matchesYear) {
                    $validator->errors()->add(
                        'class_id',
                        "La classe sélectionnée ne correspond pas à l'année scolaire choisie."
                    );
                }
            }
        });

        $data = $validator->validate();

        $schoolId = School::query()->value('id');
        if (! $schoolId) {
            return back()->withErrors(['school_id' => "Aucune école n'est configurée."])
                ->withInput();
        }

        try {
            $matricule = app(MatriculeService::class)->generateForStudent($schoolId);
        } catch (RuntimeException $exception) {
            return back()->withErrors(['academic_year' => $exception->getMessage()])
                ->withInput();
        }

        DB::transaction(function () use ($data, $schoolId, $matricule) {
            $student = Student::create([
                'school_id' => $schoolId,
                'academic_year_id' => $data['academic_year_id'],
                'admission_number' => $this->generateAdmissionNumber(
                    $data['first_name'],
                    $data['last_name'],
                    $data['enrollment_date'] ?? null
                ),
                'matricule' => $matricule,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'gender' => $data['gender'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'place_of_birth' => $data['place_of_birth'] ?? null,
                'nationality' => $data['nationality'] ?? null,
                'blood_type' => $data['blood_type'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
                'enrollment_date' => $data['enrollment_date'] ?? null,
                'previous_school' => $data['previous_school'] ?? null,
                'needs_special_care' => (bool) ($data['needs_special_care'] ?? false),
                'medical_notes' => $data['medical_notes'] ?? null,
                'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                'status' => $data['status'],
            ]);

            StudentClass::create([
                'student_id' => $student->id,
                'class_id' => $data['class_id'],
                'academic_year_id' => $data['academic_year_id'],
                'start_date' => null,
                'status' => $data['class_status'] ?? 'active',
                'assigned_at' => now(),
            ]);

            $hasParentData = collect([
                $data['parent_first_name'] ?? null,
                $data['parent_last_name'] ?? null,
                $data['parent_gender'] ?? null,
                $data['parent_relationship'] ?? null,
                $data['parent_phone'] ?? null,
                $data['parent_email'] ?? null,
                $data['parent_address'] ?? null,
                $data['parent_occupation'] ?? null,
                $data['parent_employer'] ?? null,
                $data['parent_national_id'] ?? null,
            ])->filter(fn ($value) => filled($value))->isNotEmpty();

            if ($hasParentData) {
                $parent = ParentProfile::create([
                    'first_name' => $data['parent_first_name'],
                    'last_name' => $data['parent_last_name'],
                    'gender' => $data['parent_gender'] ?? null,
                    'relationship' => $data['parent_relationship'] ?? null,
                    'phone' => $data['parent_phone'] ?? null,
                    'email' => $data['parent_email'] ?? null,
                    'address' => $data['parent_address'] ?? null,
                    'occupation' => $data['parent_occupation'] ?? null,
                    'employer' => $data['parent_employer'] ?? null,
                    'national_id' => $data['parent_national_id'] ?? null,
                    'is_primary' => (bool) ($data['parent_is_primary'] ?? false),
                    'status' => 'active',
                ]);

                StudentParent::create([
                    'student_id' => $student->id,
                    'parent_id' => $parent->id,
                    'is_primary' => (bool) ($data['parent_is_primary'] ?? false),
                    'has_custody' => (bool) ($data['parent_has_custody'] ?? false),
                    'notes' => $data['parent_notes'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('students.index')
            ->with('status', "L'élève a été ajouté avec succès.");
    }

    public function show(int $id): JsonResponse
    {
        $student = Student::query()->findOrFail($id);

        $studentClass = StudentClass::query()
            ->where('student_classes.student_id', $student->id)
            ->leftJoin('classes', 'student_classes.class_id', '=', 'classes.id')
            ->select('classes.name as name', 'student_classes.status', 'student_classes.start_date')
            ->latest('student_classes.assigned_at')
            ->first();

        $parent = StudentParent::query()
            ->where('student_parents.student_id', $student->id)
            ->leftJoin('parents', 'student_parents.parent_id', '=', 'parents.id')
            ->select(
                'parents.first_name',
                'parents.last_name',
                'parents.phone',
                'parents.email',
                'parents.relationship',
                'student_parents.is_primary'
            )
            ->first();

        $grades = Grade::query()
            ->where('grades.student_id', $student->id)
            ->leftJoin('assessments', 'grades.assessment_id', '=', 'assessments.id')
            ->select('assessments.title as assessment', 'grades.score', 'grades.remark', 'grades.graded_at')
            ->orderByDesc('grades.graded_at')
            ->get();

        $payments = Payment::query()
            ->where('payments.student_id', $student->id)
            ->leftJoin('fees', 'payments.fee_id', '=', 'fees.id')
            ->select(
                'fees.name as fee',
                'payments.amount_paid',
                'payments.balance_due',
                'payments.payment_date',
                'payments.method',
                'payments.status',
                'payments.reference'
            )
            ->orderByDesc('payments.payment_date')
            ->get();

        $documents = StudentDocument::query()
            ->where('student_documents.student_id', $student->id)
            ->leftJoin('documents', 'student_documents.document_id', '=', 'documents.id')
            ->select(
                'documents.name',
                'documents.category',
                'student_documents.status',
                'student_documents.is_required'
            )
            ->orderBy('documents.name')
            ->get();

        return response()->json([
            'student' => $student,
            'class' => $studentClass,
            'parent' => $parent,
            'grades' => $grades,
            'payments' => $payments,
            'documents' => $documents,
        ]);
    }

    private function generateAdmissionNumber(string $firstName, string $lastName, ?string $enrollmentDate): string
    {
        $year = $enrollmentDate
            ? Carbon::parse($enrollmentDate)->format('Y')
            : now()->format('Y');

        $lettersSource = preg_replace('/[^A-Za-z]/', '', $lastName . $firstName);
        $letters = strtoupper(substr($lettersSource, 0, 3));
        $letters = str_pad($letters, 3, 'X');

        $candidate = $year . '-' . $letters;
        $alphabet = range('A', 'Z');

        while (Student::query()->where('admission_number', $candidate)->exists()) {
            $letters .= $alphabet[array_rand($alphabet)];
            $candidate = $year . '-' . $letters;
        }

        return $candidate;
    }
}
