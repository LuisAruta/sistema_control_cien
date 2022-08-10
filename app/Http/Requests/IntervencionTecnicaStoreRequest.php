<?php

namespace App\Http\Requests;

use App\Rules\DelegacionUser;
use App\Rules\DelitoporTipoIntervencion;
use Illuminate\Foundation\Http\FormRequest;

class IntervencionTecnicaStoreRequest extends FormRequest
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
            'intervenciontecnica.fecha_hora' => ['required'],
            'intervenciontecnica.descripcion' => ['required', 'string', 'max:2000'],
            'intervenciontecnica.plano' => ['required'],
            'intervenciontecnica.foto' => ['required'],
            'intervenciontecnica.video' => ['required'],
            'intervenciontecnica.perito_usuario_id' => ['required', 'integer', 'exists:users,id'],
            'intervenciontecnica.tecnico_revelador_usuario_id' => ['nullable', 'integer', 'exists:users,id'],
            'intervenciontecnica.secretario_usuario_id' => ['nullable', 'integer', 'exists:users,id'],
            'intervenciontecnica.planimetrista_usuario_id' => ['nullable', 'integer', 'exists:users,id'],
            'intervenciontecnica.fotografo_usuario_id' => ['nullable', 'integer', 'exists:users,id'],
            'intervenciontecnica.video_usuario_id' => ['nullable', 'integer', 'exists:users,id'],
            'intervenciontecnica.delegacion_dependencia_id' => ['required', 'integer', 'exists:dependencias,id', new DelegacionUser],
            'intervenciontecnica.interviniento_dependencia_id' => ['required', 'integer', 'exists:dependencias,id'],
            'intervenciontecnica.tipo_intervencion_tecnica_id' => ['required', 'integer', 'exists:tipo_intervencion_tecnica,id'],
            'intervenciontecnica.tipo_colision_id' => ['nullable', 'required_if:tipo_intervencion_tecnica_id,2', 'integer', 'exists:tipo_colision,id'],
            'intervenciontecnica.delito_id' => ['required', 'integer', 'exists:delitos,id', new DelitoporTipoIntervencion([$this->request->get('intervenciontecnica')['tipo_intervencion_tecnica_id']])],
            'intervenciontecnica.estado_id' => ['required', 'integer', 'exists:estados,id'],
            'lugar.departamento' => ['required', 'string', 'max:100'],
            'lugar.barrio' => ['nullable', 'string', 'max:100'],
            'lugar.localidad' => ['nullable', 'string', 'max:100'],
            'lugar.calle' => ['nullable', 'string', 'max:100'],
            'lugar.numero' => ['nullable', 'string', 'max:10'],
            'lugar.numero_departamento' => ['nullable', 'string', 'max:10'],
            'expediente.numero_expediente' => ['nullable', 'max:20'],
            'expediente.caratula' => ['nullable', 'max:200'],
            'indicios.*.tipo_indicio_id' => ['required', 'integer', 'exists:tipo_indicio,id'],
            'indicios.*.cantidad' => ['required', 'integer'],
            'indicios.*.detalle' => ['nullable', 'string', 'max:80'],
            'informes_laboratorio.*.tipo_informes_laboratorio_id' => ['required', 'integer', 'exists:tipo_informes_laboratorio,id'],
            'informes_laboratorio.*.numero_informe' => ['required', 'string', 'max:30'],
            'detenidos.*.nombre' => ['required', 'string'],
            'detenidos.*.apellido' => ['required', 'string'],
            'detenidos.*.documento.tipo_documento' => ['required', 'string', 'max:20'],
            'detenidos.*.documento.numero' => ['required', 'integer'],
            'detenidos.*.fecha_nacimiento' => ['required'],
            'victimas.*.nombre' => ['required', 'string'],
            'victimas.*.apellido' => ['required', 'string'],
            'victimas.*.documento.tipo_documento' => ['required', 'string', 'max:20'],
            'victimas.*.documento.numero' => ['required', 'integer'],
            'victimas.*.fecha_nacimiento' => ['required'],
        ];
    }
}
