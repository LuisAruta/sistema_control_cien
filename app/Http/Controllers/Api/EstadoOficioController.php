<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EstadoOficioStoreRequest;
use App\Http\Requests\EstadoOficioUpdateRequest;
use App\Http\Resources\EstadoOficioCollection;
use App\Http\Resources\EstadoOficioResource;
use App\Models\EstadoOficio;
use Illuminate\Http\Request;

class EstadoOficioController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\EstadoOficioCollection
     */
    public function index(Request $request)
    {
        $estados = EstadoOficio::all();

        return new EstadoOficioCollection($estados);
    }

    /**
     * @param \App\Http\Requests\EstadoOficioStoreRequest $request
     * @return \App\Http\Resources\EstadoOficioResource
     */
    public function store(EstadoOficioStoreRequest $request)
    {
        $estado = EstadoOficio::create($request->validated());

        return new EstadoOficioResource($estado);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\EstadoOficio $estado
     * @return \App\Http\Resources\EstadoOficioResource
     */
    public function show(Request $request, EstadoOficio $estado)
    {
        return new EstadoOficioResource($estado);
    }

    /**
     * @param EstadoOficioUpdateRequest $request
     * @param $estado
     * @return EstadoOficioResource
     */
    public function update(EstadoOficioUpdateRequest $request, EstadoOficio $estado)
    {
        $estado->update($request->validated());

        return new EstadoOficioResource($estado);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\EstadoOficio $estado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, EstadoOficio $estado)
    {
        $estado->delete();

        return response()->noContent();
    }
}
