<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TipoColisionStoreRequest;
use App\Http\Requests\TipoColisionUpdateRequest;
use App\Http\Resources\TipoColisionCollection;
use App\Http\Resources\TipoColisionResource;
use App\Models\TipoColision;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TipoColisionController extends Controller
{
    /**
     * @param Request $request
     * @return TipoColisionCollection
     */
    public function index(Request $request)
    {
        $tipos = TipoColision::all();

        return new TipoColisionCollection($tipos);
    }

    /**
     * @param TipoColisionStoreRequest $request
     * @return TipoColisionResource
     */
    public function store(TipoColisionStoreRequest $request)
    {
        $tipo = TipoColision::create($request->validated());

        return new TipoColisionResource($tipo);
    }

    /**
     * @param Request $request
     * @param TipoColision $tipo
     * @return TipoColisionResource
     */
    public function show(Request $request, TipoColision $tipo)
    {
        return new TipoColisionResource($tipo);
    }

    /**
     * @param TipoColisionUpdateRequest $request
     * @param TipoColision $tipo
     * @return TipoColisionResource
     */
    public function update(TipoColisionUpdateRequest $request, TipoColision $tipo)
    {
        $tipo->update($request->validated());

        return new TipoColisionResource($tipo);
    }

    /**
     * @param Request $request
     * @param TipoColision $tipo
     * @return Response
     */
    public function destroy(Request $request, TipoColision $tipo)
    {
        $tipo->delete();

        return response()->noContent();
    }
}
