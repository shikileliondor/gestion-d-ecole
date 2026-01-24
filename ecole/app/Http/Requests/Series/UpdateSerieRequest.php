<?php

namespace App\Http\Requests\Series;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSerieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $serieId = $this->resolveRouteId('serie');

        return [
            'code' => ['sometimes', 'string', 'max:10', Rule::unique('series', 'code')->ignore($serieId)],
            'libelle' => ['sometimes', 'nullable', 'string', 'max:50'],
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
