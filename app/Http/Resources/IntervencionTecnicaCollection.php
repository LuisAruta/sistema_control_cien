<?php

namespace App\Http\Resources;

use App\Models\TipoIndicio;
use Illuminate\Http\Resources\Json\ResourceCollection;

class IntervencionTecnicaCollection extends ResourceCollection
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
            $item['tecnico_revelador_usuario'] = $item->tecnicoReveladorUsuario;
            if( $item->tecnicoReveladorUsuario ){
                $item->tecnicoReveladorUsuario->documento;
                $item->tecnicoReveladorUsuario->dependencia;
            }
            $item['secretario_usuario'] = $item->secretarioUsuario;
            if( $item->secretarioUsuario ){
                $item->secretarioUsuario->documento;
                $item->secretarioUsuario->dependencia;
            }
            $item['planimetrista_usuario'] = $item->planimetristaUsuario;
            if ($item->planimetristaUsuario){
                $item->planimetristaUsuario->documento;
                $item->planimetristaUsuario->dependencia;
            }
            $item['fotografo_usuario'] = $item->fotografoUsuario;
            if ($item->fotografoUsuario){
                $item->fotografoUsuario->documento;
                $item->fotografoUsuario->dependencia;
            }
            $item['video_usuario'] = $item->videoUsuario;
            if($item->videoUsuario){
                $item->videoUsuario->documento;
                $item->videoUsuario->dependencia;
            }
            $item['indicios'] = $item->indicios;
            if($item->indicios){
                foreach ($item->indicios as $valor){
                    $valor->tipoIndicio;
                }
            }
            $item['detenidos'] = $item->detenidos;
            if($item->detenidos){
                foreach ($item->detenidos as $valor){
                    $valor->documento;
                }
            }
            $item['victimas'] = $item->victimas;
            if($item->victimas){
                foreach ($item->victimas as $valor){
                    $valor->documento;
                }
            }
            $item['informes_laboratorio'] = $item->informesLaboratorio;
            if($item->informesLaboratorio){
                foreach ($item->informes_laboratorio as $valor){
                    $valor->tipoInformesLaboratorio;
                }
            }
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
