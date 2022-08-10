<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndicioResource extends JsonResource
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
            'descripcion' => $this->descripcion,
            'muestrable' => $this->muestrable,
            'tipo_indicio' => $this->tipoIndicio->nombre,
        ];
    }
}
