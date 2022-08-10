<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\ResourceCollection;

class TipoIntervencionTecnicaCollection extends ResourceCollection
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
            $item['delitos'] = $item->delitos;
        });
        return [
            'data' => $this->collection,
        ];
    }
}
