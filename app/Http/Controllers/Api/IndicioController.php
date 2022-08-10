<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndicioStoreRequest;
use App\Http\Requests\IndicioUpdateRequest;
use App\Http\Resources\IndicioCollection;
use App\Http\Resources\IndicioResource;
use App\Models\Indicio;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IndicioController extends Controller
{
    /**
     * @param Request $request
     * @return IndicioCollection
     */
    public function index(Request $request)
    {
        $tipos = Indicio::all();

        return new IndicioCollection($tipos);
    }

    /**
     * @param IndicioStoreRequest $request
     * @return IndicioResource
     */
    public function store(IndicioStoreRequest $request)
    {
        $tipo = Indicio::create($request->validated());

        return new IndicioResource($tipo);
    }

    /**
     * @param Request $request
     * @param Indicio $tipo
     * @return IndicioResource
     */
    public function show(Request $request, Indicio $tipo)
    {
        return new IndicioResource($tipo);
    }

    /**
     * @param IndicioUpdateRequest $request
     * @param Indicio $tipo
     * @return IndicioResource
     */
    public function update(IndicioRequest $request, Indicio $tipo)
    {
        $tipo->update($request->validated());

        return new IndicioResource($tipo);
    }

    /**
     * @param Request $request
     * @param Indicio $tipo
     * @return Response
     */
    public function destroy(Request $request, Indicio $tipo)
    {
        $tipo->delete();

        return response()->noContent();
    }
}
