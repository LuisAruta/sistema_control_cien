<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NecroResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'numero_de_registro' => $this->numero_de_registro,
            'fecha_hora' => $this->fecha_hora,
            'expediente' => $this->expediente,
            'perito_usuario' => $this->peritoUsuario,
            'cadaver_nombre' => $this->cadaver_nombre,
            'cadaver_documento' => $this->cadaverDocumento,
            'observaciones' => $this->observaciones,
            'legajo_cuerpo_medico_forense' => $this->legajo_cuerpo_medico_forense,
            'lugar' => $this->lugar,
            'foto' => $this->foto,
            'delegacion_dependencia' => $this->delegacionDependencia,
            'interviniento_dependencia' => $this->intervinientoDependencia,
            'estado' => $this->estado,
            'editable' => $this->is_Editable,
            'oficios' => $this->oficios,
            'cantidad_oficios_pendientes' => $this->cantidad_oficios_pendientes
        ];
    }
}
