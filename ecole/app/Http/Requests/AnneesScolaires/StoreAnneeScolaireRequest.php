<?php

namespace App\Http\Requests\AnneesScolaires;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnneeScolaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'libelle' => ['required', 'string', 'max:20', 'unique:annees_scolaires,libelle'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date', 'after:date_debut'],
            'statut' => ['required', 'in:ACTIVE,CLOTUREE,ARCHIVEE'],
        ];
    }
}
