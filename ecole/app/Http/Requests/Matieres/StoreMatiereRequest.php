<?php

namespace App\Http\Requests\Matieres;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatiereRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:80', 'unique:matieres,nom'],
            'code' => ['nullable', 'string', 'max:20', 'unique:matieres,code'],
            'actif' => ['required', 'boolean'],
        ];
    }
}
