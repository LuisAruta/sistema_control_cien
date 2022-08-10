<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\TipoIntervencionTecnicaStoreRequest;
use App\Http\Requests\TipoIntervencionTecnicaUpdateRequest;
use App\Http\Resources\TipoIntervencionTecnicaCollection;
use App\Http\Resources\TipoIntervencionTecnicaResource;
use App\Models\TipoIntervencionTecnica;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class TipoIntervencionTecnicaController extends Controller
{
    /**
     * @param Request $request
     * @return TipoIntervencionTecnicaCollection
     */

    public function index(Request $request)
    {
        $tipos = TipoIntervencionTecnica::all();

        return new TipoIntervencionTecnicaCollection($tipos);
    }

    /**
     * @param TipoIntervencionTecnicaStoreRequest $request
     * @return TipoIntervencionTecnicaResource
     */
    public function store(TipoIntervencionTecnicaStoreRequest $request)
    {
        $tipo = TipoIntervencionTecnica::create($request->validated());

        return new TipoIntervencionTecnicaResource($tipo);
    }

    /**
     * @param Request $request
     * @param TipoIntervencionTecnica $tipo
     * @return TipoIntervencionTecnicaResource
     */
    public function show(Request $request, TipoIntervencionTecnica $tipo)
    {
        return new TipoIntervencionTecnicaResource($tipo);
    }

    /**
     * @param TipoIntervencionTecnicaUpdateRequest $request
     * @param TipoIntervencionTecnica $tipo
     * @return TipoIntervencionTecnicaResource
     */
    public function update(TipoIntervencionTecnicaUpdateRequest $request, TipoIntervencionTecnica $tipo)
    {
        $tipo->update($request->validated());

        return new TipoIntervencionTecnicaResource($tipo);
    }

    /**
     * @param Request $request
     * @param TipoIntervencionTecnica $tipo
     * @return Response
     */
    public function destroy(Request $request, TipoIntervencionTecnica $tipo)
    {
        $tipo->delete();

        return response()->noContent();
    }
}

