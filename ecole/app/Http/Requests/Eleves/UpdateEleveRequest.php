<?php

namespace App\Http\Requests\Eleves;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEleveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $eleveId = $this->resolveRouteId('eleve');

        return [
            'matricule' => ['sometimes', 'string', 'max:32', Rule::unique('eleves', 'matricule')->ignore($eleveId)],
            'nom' => ['sometimes', 'string', 'max:100'],
            'prenoms' => ['sometimes', 'string', 'max:150'],
            'sexe' => ['sometimes', 'nullable', 'in:M,F,AUTRE'],
            'date_naissance' => ['sometimes', 'nullable', 'date', 'before_or_equal:today'],
            'lieu_naissance' => ['sometimes', 'nullable', 'string', 'max:120'],
            'nationalite' => ['sometimes', 'nullable', 'string', 'max:80'],
            'photo_path' => ['sometimes', 'nullable', 'string', 'max:2048'],
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
