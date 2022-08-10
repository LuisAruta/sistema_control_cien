<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class OficioResource extends JsonResource
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
            'fecha_ingreso' => $this->fecha_ingreso,
            'instancia_solicitante' => $this->instanciaSolicitante,
            'observacion' => $this->observacion,
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'oficiable_id' => $this->oficiable_id,
            'oficiable_type' => $this->oficiable_type,
            'estado_oficio' => $this->estadoOficio,
            'estado_oficio_id' => $this->estado_oficio_id,
            'historico_estado_oficio' => $this->historicoEstadoOficio,
        ];
    }
}
