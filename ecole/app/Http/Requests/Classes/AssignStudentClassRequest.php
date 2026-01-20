<?php

namespace App\Http\Requests\Classes;

use Illuminate\Foundation\Http\FormRequest;

class AssignStudentClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'start_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,transferred,completed'],
        ];
    }

    public function getErrorBag(): string
    {
        return 'assignStudentForm';
    }
}
