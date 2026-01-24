<?php

namespace App\Http\Requests\Classes;

use Illuminate\Foundation\Http\FormRequest;

class StoreClasseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'annee_scolaire_id' => ['required', 'integer', 'exists:annees_scolaires,id'],
            'niveau_id' => ['required', 'integer', 'exists:niveaux,id'],
            'serie_id' => ['nullable', 'integer', 'exists:series,id'],
            'nom' => ['required', 'string', 'max:60'],
            'effectif_max' => ['nullable', 'integer', 'min:1'],
            'actif' => ['required', 'boolean'],
        ];
    }
}
