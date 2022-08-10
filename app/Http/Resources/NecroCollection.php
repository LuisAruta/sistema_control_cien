<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NecroCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->map(function ($item) {
            $item['perito_usuario'] = $item->peritoUsuario;
            $item->peritoUsuario->documento;
            $item->peritoUsuario->dependencia;
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
