<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IntervencionTecnicaResource extends JsonResource
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
            'descripcion' => $this->descripcion,
            'plano' => $this->plano,
            'foto' => $this->foto,
            'video' => $this->video,
            'delito' => $this->delito,
            'delegacion_dependencia' => $this->delegacionDependencia,
            'interviniento_dependencia' => $this->intervinientoDependencia,
            'lugar' => $this->lugar,
            'estado' => $this->estado,
            'perito_usuario' => $this->peritoUsuario,
            'tecnico_revelador_usuario' => $this->tecnicoReveladorUsuario,
            'secretario_usuario' => $this->secretarioUsuario,
            'planimetrista_usuario' => $this->planimetristaUsuario,
            'fotografo_usuario' => $this->fotografoUsuario,
            'video_usuario' => $this->videoUsuario,
            'tipo_colision' => $this->tipoColision,
            'tipo_intervencion_tecnica' => $this->tipoIntervencionTecnica,
            'editable' => $this->is_Editable,
            'indicios' => $this->indicios,
            'detenidos' => $this->detenidos,
            'victimas' => $this->victimas,
            'oficios' => $this->oficios,
            'informes_laboratorio' => $this->informes_laboratorio,
            'cantidad_oficios_pendientes' => $this->cantidad_oficios_pendientes
        ];
    }
}
