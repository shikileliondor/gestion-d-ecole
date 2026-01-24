<?php

namespace App\Http\Requests\AnneesScolaires;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAnneeScolaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $anneeScolaireId = $this->resolveRouteId('anneeScolaire', 'annee_scolaire');

        return [
            'libelle' => [
                'sometimes',
                'string',
                'max:20',
                Rule::unique('annees_scolaires', 'libelle')->ignore($anneeScolaireId),
            ],
            'date_debut' => ['sometimes', 'date'],
            'date_fin' => ['sometimes', 'date', 'after:date_debut'],
            'statut' => ['sometimes', 'in:ACTIVE,CLOTUREE,ARCHIVEE'],
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
