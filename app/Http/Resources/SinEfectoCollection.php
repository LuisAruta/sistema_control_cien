<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SinEfectoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->map(function ($item) {
            $item['usuario'] = $item->usuario;
            $item->usuario->documento;
            $item->usuario->dependencia;
            $item['oficios'] = $item->oficios;
            if($item->oficios){
                foreach ($item->oficios as $valor){
                    $valor->instanciaSolicitante;
                    $valor->estadoOficio;
                }
            }
        });
        return [
            'data' => $this->collection,
        ];
    }
}
