<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TrasladoCollection extends ResourceCollection
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
            $item['perito_usuario'] = $item->peritoUsuario;
            $item->peritoUsuario->documento;
            $item->peritoUsuario->dependencia;
            $item['acompanante_usuario'] = $item->acompananteUsuario;
            $item->acompananteUsuario->documento;
            $item->acompananteUsuario->dependencia;
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
