<?php

namespace App\Http\Requests\Classes;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClasseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'annee_scolaire_id' => ['sometimes', 'integer', 'exists:annees_scolaires,id'],
            'niveau_id' => ['sometimes', 'integer', 'exists:niveaux,id'],
            'serie_id' => ['sometimes', 'nullable', 'integer', 'exists:series,id'],
            'nom' => ['sometimes', 'string', 'max:60'],
            'effectif_max' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'actif' => ['sometimes', 'boolean'],
        ];
    }
}
