<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TipoIndicioStoreRequest;
use App\Http\Requests\TipoIndicioUpdateRequest;
use App\Http\Resources\TipoIndicioCollection;
use App\Http\Resources\TipoIndicioResource;
use App\Models\TipoIndicio;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TipoIndicioController extends Controller
{
    /**
     * @param Request $request
     * @return TipoIndicioCollection
     */
    public function index(Request $request)
    {
        $tipos = TipoIndicio::all();

        return new TipoIndicioCollection($tipos);
    }

    /**
     * @param TipoIndicioStoreRequest $request
     * @return TipoIndicioResource
     */
    public function store(TipoIndicioStoreRequest $request)
    {
        $tipo = TipoIndicio::create($request->validated());

        return new TipoIndicioResource($tipo);
    }

    /**
     * @param Request $request
     * @param TipoIndicio $tipo
     * @return TipoIndicioResource
     */
    public function show(Request $request, TipoIndicio $tipo)
    {
        return new TipoIndicioResource($tipo);
    }

    /**
     * @param TipoIndicioUpdateRequest $request
     * @param TipoIndicio $tipo
     * @return TipoIndicioResource
     */
    public function update(TipoIndicioUpdateRequest $request, TipoIndicio $tipo)
    {
        $tipo->update($request->validated());

        return new TipoIndicioResource($tipo);
    }

    /**
     * @param Request $request
     * @param TipoIndicio $tipo
     * @return Response
     */
    public function destroy(Request $request, TipoIndicio $tipo)
    {
        $tipo->delete();

        return response()->noContent();
    }
}
