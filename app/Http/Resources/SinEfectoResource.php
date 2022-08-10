<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SinEfectoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'numero_de_registro' => $this->numero_de_registro,
            'fecha_hora_registro' => $this->fecha_hora_registro,
            'fecha_hora_solicitud' => $this->fecha_hora_solicitud,
            'usuario' => $this->usuario,
            'delegacion_dependencia' => $this->delegacionDependencia,
            'lugar' => $this->lugar,
            'funcionario' => $this->funcionario,
            'descripcion' => $this->descripcion,
            'editable' => $this->is_Editable,
            'oficios' => $this->oficios,
            'cantidad_oficios_pendientes' => $this->cantidad_oficios_pendientes
        ];
    }
}
