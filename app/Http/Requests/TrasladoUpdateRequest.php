<?php

namespace App\Http\Requests;

use App\Rules\DelegacionUser;
use App\Rules\NumeroUnicoRegistro;
use Illuminate\Foundation\Http\FormRequest;

class TrasladoUpdateRequest extends FormRequest
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
            'traslado.fecha_hora' => ['required'],
            'traslado.perito_usuario_id' => ['required', 'integer', 'exists:users,id'],
            'traslado.acompanante_usuario_id' => ['integer', 'exists:users,id'],
            'traslado.delegacion_dependencia_id' => ['required', 'integer', 'exists:dependencias,id', new DelegacionUser],
            'traslado.interviniento_dependencia_id' => ['required', 'integer', 'exists:dependencias,id'],
            'traslado.legajo_cuerpo_medico_forense' => ['required', 'string', 'max:50'],
            'traslado.numero_de_registro' => ['required', 'integer', new NumeroUnicoRegistro(['traslados', $this->request->get('traslado')['id'], $this->request->get('traslado')['delegacion_dependencia_id']])],
            'cadaver.cadaver_nombre' => ['required', 'string', 'max:100'],
            'cadaver.cadaver_documento.tipo_documento' => ['nullable', 'string', 'max:20'],
            'cadaver.cadaver_documento.numero' => ['nullable', 'string', 'max:20'],
            'cadaver.identificado' => ['nullable', 'boolean'],
            'cadaver.foto' => ['required'],
            'cadaver.observaciones' => ['nullable', 'string', 'max:250'],
            'lugar.departamento' => ['required', 'string', 'max:100'],
            'lugar.barrio' => ['nullable', 'string', 'max:100'],
            'lugar.localidad' => ['nullable', 'string', 'max:100'],
            'lugar.calle' => ['nullable', 'string', 'max:100'],
            'lugar.numero' => ['nullable', 'string', 'max:10'],
            'lugar.numero_departamento' => ['nullable', 'string', 'max:10'],
            'expediente.numero_expediente' => ['nullable', 'max:20'],
            'expediente.caratula' => ['nullable', 'max:200'],
        ];
    }
}
