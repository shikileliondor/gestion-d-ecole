<?php

namespace App\Http\Requests\Niveaux;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNiveauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $niveauId = $this->resolveRouteId('niveau');

        return [
            'code' => ['sometimes', 'string', 'max:20', Rule::unique('niveaux', 'code')->ignore($niveauId)],
            'ordre' => ['sometimes', 'integer', Rule::unique('niveaux', 'ordre')->ignore($niveauId)],
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
