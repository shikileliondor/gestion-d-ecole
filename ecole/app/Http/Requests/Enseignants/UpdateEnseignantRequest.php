<?php

namespace App\Http\Requests\Enseignants;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEnseignantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $enseignantId = $this->resolveRouteId('enseignant');

        return [
            'matricule' => ['sometimes', 'string', 'max:32', Rule::unique('enseignants', 'matricule')->ignore($enseignantId)],
            'nom' => ['sometimes', 'nullable', 'string', 'max:100'],
            'prenoms' => ['sometimes', 'nullable', 'string', 'max:150'],
            'sexe' => ['sometimes', 'nullable', 'in:M,F,AUTRE'],
            'telephone_1' => ['sometimes', 'nullable', 'string', 'max:30'],
            'telephone_2' => ['sometimes', 'nullable', 'string', 'max:30'],
            'email' => ['sometimes', 'nullable', 'email', 'max:150'],
            'specialite' => ['sometimes', 'nullable', 'string', 'max:80'],
            'photo_path' => ['sometimes', 'nullable', 'string', 'max:2048'],
            'type_enseignant' => ['sometimes', 'in:PERMANENT,VACATAIRE,STAGIAIRE'],
            'date_debut_service' => ['sometimes', 'date'],
            'date_fin_service' => ['sometimes', 'nullable', 'date', 'after_or_equal:date_debut_service'],
            'statut' => ['sometimes', 'in:ACTIF,SUSPENDU,PARTI'],
        ];
    }

    private function resolveRouteId(string ...$keys): ?int
    {
        foreach ($keys as $key) {
            $value = $this->route($key);
            if ($value instanceof Model) {
                return (int) $value->getKey();
            }
            if (is_numeric($value)) {
                return (int) $value;
            }
        }

        return null;
    }
}
