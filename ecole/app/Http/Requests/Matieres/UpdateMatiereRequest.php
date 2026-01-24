<?php

namespace App\Http\Requests\Matieres;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMatiereRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $matiereId = $this->resolveRouteId('matiere');

        return [
            'nom' => ['sometimes', 'string', 'max:80', Rule::unique('matieres', 'nom')->ignore($matiereId)],
            'code' => ['sometimes', 'nullable', 'string', 'max:20', Rule::unique('matieres', 'code')->ignore($matiereId)],
            'actif' => ['sometimes', 'boolean'],
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
