<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LugarUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'latitud' => ['required', 'string', 'max:100'],
            'longitud' => ['required', 'string', 'max:100'],
            'departamento' => ['required', 'string', 'max:100'],
            'barrio' => ['required', 'string', 'max:100'],
            'localidad' => ['required', 'string', 'max:100'],
            'calle' => ['nullable', 'string', 'max:100'],
            'numero' => ['nullable', 'string', 'max:10'],
            'numero_departamento' => ['nullable', 'string', 'max:10'],
        ];
    }
}
