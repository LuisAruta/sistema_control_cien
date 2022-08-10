<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EstadoStoreRequest;
use App\Http\Requests\EstadoUpdateRequest;
use App\Http\Resources\EstadoCollection;
use App\Http\Resources\EstadoResource;
use App\Models\Estado;
use Illuminate\Http\Request;

class EstadoController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\EstadoCollection
     */
    public function index(Request $request)
    {
        $estados = Estado::all();

        return new EstadoCollection($estados);
    }

    /**
     * @param \App\Http\Requests\EstadoStoreRequest $request
     * @return \App\Http\Resources\EstadoResource
     */
    public function store(EstadoStoreRequest $request)
    {
        $estado = Estado::create($request->validated());

        return new EstadoResource($estado);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Estado $estado
     * @return \App\Http\Resources\EstadoResource
     */
    public function show(Request $request, Estado $estado)
    {
        return new EstadoResource($estado);
    }

    /**
     * @param EstadoUpdateRequest $request
     * @param $estado
     * @return EstadoResource
     */
    public function update(EstadoUpdateRequest $request, Estado $estado)
    {
        $estado->update($request->validated());

        return new EstadoResource($estado);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Estado $estado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Estado $estado)
    {
        $estado->delete();

        return response()->noContent();
    }
}
