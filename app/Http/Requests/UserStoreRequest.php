<?php

namespace App\Http\Requests;

use App\Rules\DocumentoUnicoUser;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'usuario.name' => ['required', 'string', 'max:100'],
            'usuario.email' => ['required', 'unique:users,email'],
            'usuario.dependencia_id' => ['required', 'integer', 'exists:dependencias,id'],
            'usuario.nombre_completo' => ['required', 'string', 'max:100'],
            'usuario.perito' => ['boolean'],
            'usuario.rolesUsuario' => ['required'],
            'documento.tipo_documento' => ['required', 'string', 'max:20'],
            'documento.numero' => ['required', new DocumentoUnicoUser([$this->request->get('documento')['tipo_documento'], $this->request->get('documento')['numero'], null]),'string', 'max:13'],
        ];
    }
}
