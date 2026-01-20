<?php

namespace App\Http\Requests\Classes;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassHeadcountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'manual_headcount' => ['required', 'integer', 'min:0'],
        ];
    }

    public function getErrorBag(): string
    {
        return 'headcountForm';
    }
}
