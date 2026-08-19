<?php

namespace App\Http\Requests;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEgresoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'categoria_id' => [
                'bail',
                'required',
                'integer',
                Rule::exists('categorias', 'id')->where(
                    fn (Builder $query) => $query
                        ->where('user_id', $userId)
                        ->where('tipo', 'egreso')
                ),
            ],
            'subcategoria_id' => [
                'nullable',
                'integer',
                Rule::exists('subcategorias', 'id')->where(
                    fn (Builder $query) => $query
                        ->where('categoria_id', $this->input('categoria_id'))
                        ->whereIn('categoria_id', Categoria::query()
                            ->select('id')
                            ->where('user_id', $userId)
                            ->where('tipo', 'egreso'))
                ),
            ],
            'fecha' => ['required', 'date_format:Y-m-d'],
            'descripcion' => ['required', 'string', 'max:150'],
            'monto' => ['required', 'string', 'regex:/^\d{1,10}(?:\.\d{1,2})?$/'],
            'notas' => ['nullable', 'string'],
        ];
    }
}
