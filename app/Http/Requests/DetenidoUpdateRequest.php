<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetenidoUpdateRequest extends FormRequest
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
            'nombre' => ['required', 'string', 'max:20'],
            'apellido' => ['required', 'string', 'max:20'],
            'documento.tipo_documento' => ['required', 'string', 'max:20'],
            'documento.numero' => ['required', 'string', 'max:11'],
            'fecha_nacimiento' => ['required'],
        ];
    }
}
