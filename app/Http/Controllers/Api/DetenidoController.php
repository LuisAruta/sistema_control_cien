<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetenidoController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\DetenidoCollection
     */
    public function index(Request $request)
    {
        $Detenidos = Detenido::orderBy('apellido', 'ASC')
            ->get();

        return new DetenidoCollection($Detenidos);
    }

    /**
     * @param \App\Http\Requests\DetenidoStoreRequest $request
     * @return \App\Http\Resources\DetenidoResource
     */
    public function store(DetenidoStoreRequest $request)
    {
        $Detenido = Detenido::create($request->validated());

        return new DetenidoResource($Detenido);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Detenido $Detenido
     * @return \App\Http\Resources\DetenidoResource
     */
    public function show(Request $request, Detenido $Detenido)
    {
        return new DetenidoResource($Detenido);
    }

    /**
     * @param \App\Http\Requests\DetenidoUpdateRequest $request
     * @param \App\Models\Detenido $Detenido
     * @return \App\Http\Resources\DetenidoResource
     */
    public function update(DetenidoUpdateRequest $request, Detenido $Detenido)
    {
        $Detenido->update($request->validated());

        return new DetenidoResource($Detenido);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Detenido $Detenido
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Detenido $Detenido)
    {
        $Detenido->delete();

        return response()->noContent();
    }
}
