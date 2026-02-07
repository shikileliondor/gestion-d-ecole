<?php

namespace App\Http\Requests\Eleves;

use Illuminate\Foundation\Http\FormRequest;

class StoreEleveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'matricule' => ['required', 'string', 'max:32', 'unique:eleves,matricule'],
            'matricule_national' => ['nullable', 'string', 'max:50'],
            'nom' => ['required', 'string', 'max:100'],
            'prenoms' => ['required', 'string', 'max:150'],
            'sexe' => ['nullable', 'in:M,F,AUTRE'],
            'date_naissance' => ['nullable', 'date', 'before_or_equal:today'],
            'lieu_naissance' => ['nullable', 'string', 'max:120'],
            'nationalite' => ['nullable', 'string', 'max:80'],
            'etablissement_origine' => ['nullable', 'string', 'max:150'],
            'date_arrivee' => ['nullable', 'date'],
            'classe_precedente' => ['nullable', 'string', 'max:100'],
            'photo_path' => ['nullable', 'string', 'max:2048'],
        ];
    }
}
