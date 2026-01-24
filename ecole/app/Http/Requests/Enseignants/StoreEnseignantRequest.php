<?php

namespace App\Http\Requests\Enseignants;

use Illuminate\Foundation\Http\FormRequest;

class StoreEnseignantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'matricule' => ['required', 'string', 'max:32', 'unique:enseignants,matricule'],
            'nom' => ['nullable', 'string', 'max:100'],
            'prenoms' => ['nullable', 'string', 'max:150'],
            'sexe' => ['nullable', 'in:M,F,AUTRE'],
            'telephone_1' => ['nullable', 'string', 'max:30'],
            'telephone_2' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'specialite' => ['nullable', 'string', 'max:80'],
            'photo_path' => ['nullable', 'string', 'max:2048'],
            'type_enseignant' => ['required', 'in:PERMANENT,VACATAIRE,STAGIAIRE'],
            'date_debut_service' => ['required', 'date'],
            'date_fin_service' => ['nullable', 'date', 'after_or_equal:date_debut_service'],
            'statut' => ['required', 'in:ACTIF,SUSPENDU,PARTI'],
        ];
    }
}
