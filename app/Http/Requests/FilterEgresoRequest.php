<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterEgresoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'anio' => ['nullable', 'integer', 'between:1900,2100'],
            'mes' => ['nullable', 'integer', 'between:1,12'],
        ];
    }
}
