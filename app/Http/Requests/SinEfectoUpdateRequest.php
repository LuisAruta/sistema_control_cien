<?php

namespace App\Http\Requests;

use App\Rules\DelegacionUser;
use App\Rules\NumeroUnicoRegistro;
use Illuminate\Foundation\Http\FormRequest;

class SinEfectoUpdateRequest extends FormRequest
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
            'sinefecto.numero_de_registro' => ['required', 'integer', new NumeroUnicoRegistro(['sin_efectos', $this->request->get('sinefecto')['id'], $this->request->get('sinefecto')['delegacion_dependencia_id']])],
            'sinefecto.fecha_hora_registro' => ['required'],
            'sinefecto.fecha_hora_solicitud' => ['required'],
            'sinefecto.delegacion_dependencia_id' => ['required', 'integer', 'exists:dependencias,id', new DelegacionUser],
            'lugar.departamento' => ['required', 'string', 'max:100'],
            'lugar.barrio' => ['nullable', 'string', 'max:100'],
            'lugar.localidad' => ['nullable', 'string', 'max:100'],
            'lugar.calle' => ['nullable', 'string', 'max:100'],
            'lugar.numero' => ['nullable', 'string', 'max:10'],
            'lugar.numero_departamento' => ['nullable', 'string', 'max:10'],
            'sinefecto.funcionario' => ['required', 'string', 'max:300'],
            'sinefecto.descripcion' => ['required', 'string', 'max:2000'],
        ];
    }

}
