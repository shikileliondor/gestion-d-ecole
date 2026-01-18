<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentDocument;
use App\Models\StudentParent;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

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
            ->addSelect([
                'average_score' => Grade::query()
                    ->selectRaw('AVG(score)')
                    ->whereColumn('grades.student_id', 'students.id'),
            ])
            ->orderBy('students.last_name')
            ->orderBy('students.first_name')
            ->get();

        return view('students.index', compact('students'));
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
}
