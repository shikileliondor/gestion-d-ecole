<?php

namespace App\Http\Controllers;

use App\Http\Requests\Students\IndexStudentRequest;
use App\Http\Requests\Students\StoreStudentRequest;
use App\Http\Requests\Students\UpdateStudentRequest;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function __construct(private readonly StudentService $studentService)
    {
    }

    /**
     * Display the student listing view.
     */
    public function index(IndexStudentRequest $request): View
    {
        $students = $this->studentService->list($request->validated());

        return view('students.index', [
            'students' => $students,
        ]);
    }

    public function store(StoreStudentRequest $request): JsonResponse
    {
        $student = $this->studentService->create($request->validated());

        return response()->json($student, 201);
    }

    public function show(\App\Models\Student $student): JsonResponse
    {
        return response()->json($this->studentService->getDetails($student));
    }

    public function update(UpdateStudentRequest $request, \App\Models\Student $student): JsonResponse
    {
        $student = $this->studentService->update($student, $request->validated());

        return response()->json($student);
    }

    public function destroy(\App\Models\Student $student): JsonResponse
    {
        $this->studentService->delete($student);

        return response()->json(null, 204);
    }
}
