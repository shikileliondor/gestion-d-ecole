<?php

namespace App\Http\Requests\Niveaux;

use Illuminate\Foundation\Http\FormRequest;

class StoreNiveauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:20', 'unique:niveaux,code'],
            'ordre' => ['required', 'integer', 'unique:niveaux,ordre'],
            'actif' => ['required', 'boolean'],
        ];
    }
}
