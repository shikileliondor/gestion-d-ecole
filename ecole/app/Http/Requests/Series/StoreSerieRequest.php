<?php

namespace App\Http\Requests\Series;

use Illuminate\Foundation\Http\FormRequest;

class StoreSerieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:10', 'unique:series,code'],
            'libelle' => ['nullable', 'string', 'max:50'],
            'actif' => ['required', 'boolean'],
        ];
    }
}
