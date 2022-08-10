<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TipoInformeLaboratorioStoreRequest;
use App\Http\Requests\TipoInformeLaboratorioUpdateRequest;
use App\Http\Resources\TipoInformeLaboratorioCollection;
use App\Http\Resources\TipoInformeLaboratorioResource;
use App\Models\TipoInformesLaboratorio;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TipoInformeLaboratorioController extends Controller
{
    /**
     * @param Request $request
     * @return TipoInformeLaboratorioCollection
     */
    public function index(Request $request)
    {
        $tipos = TipoInformesLaboratorio::all();

        return new TipoInformeLaboratorioCollection($tipos);
    }

    /**
     * @param TipoInformeLaboratorioStoreRequest $request
     * @return TipoInformeLaboratorioResource
     */
    public function store(TipoInformeLaboratorioStoreRequest $request)
    {
        $tipo = TipoInformesLaboratorio::create($request->validated());

        return new TipoInformeLaboratorioResource($tipo);
    }

    /**
     * @param Request $request
     * @param TipoInformesLaboratorio $tipo
     * @return TipoInformeLaboratorioResource
     */
    public function show(Request $request, TipoInformesLaboratorio $tipo)
    {
        return new TipoInformeLaboratorioResource($tipo);
    }

    /**
     * @param TipoInformeLaboratorioUpdateRequest $request
     * @param TipoInformesLaboratorio $tipo
     * @return TipoInformeLaboratorioResource
     */
    public function update(TipoInformeLaboratorioUpdateRequest $request, TipoInformesLaboratorio $tipo)
    {
        $tipo->update($request->validated());

        return new TipoInformeLaboratorioResource($tipo);
    }

    /**
     * @param Request $request
     * @param TipoInformesLaboratorio $tipo
     * @return Response
     */
    public function destroy(Request $request, TipoInformesLaboratorio $tipo)
    {
        $tipo->delete();

        return response()->noContent();
    }
}
