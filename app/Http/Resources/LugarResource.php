<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LugarResource extends JsonResource
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
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'departamento' => $this->departamento,
            'barrio' => $this->barrio,
            'localidad' => $this->localidad,
            'calle' => $this->calle,
            'numero' => $this->numero,
            'numero_departamento' => $this->numero_departamento,
        ];
    }
}
