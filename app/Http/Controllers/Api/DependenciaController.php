<?php

namespace App\Http\Controllers\Api;

use App\Constant\PermissionConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\DependenciaStoreRequest;
use App\Http\Requests\DependenciaUpdateRequest;
use App\Http\Resources\DependenciaCollection;
use App\Http\Resources\DependenciaResource;
use App\Models\Dependencia;
use App\Traits\ValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DependenciaController extends Controller
{
    /**
     * @param Request $request
     * @return DependenciaCollection
     */
    use ValidationTrait;

    public function index(Request $request)
    {
        $this->validatePermission(PermissionConstant::CONSULTAR_DEPENDENCIA);
        $query = Dependencia::query();

            if ($request->filtros) {
                if ($request->filtros["nombre"]) {
                    $query->whereRaw("unaccent(lower(nombre)) ilike unaccent('%" . strtolower($request->filtros["nombre"]) . "%')");
                }
                if ($request->filtros["grupo"]) {
                    $query->whereRaw("unaccent(lower(grupo)) ilike unaccent('%" . strtolower($request->filtros["grupo"]) . "%')");
                }
                if ($request->filtros["cientifica"]) {
                    $query->where('cientifica', $request->filtros["cientifica"] === 'SI');
                }
            } else {
                $query->whereRaw("unaccent(lower(nombre)) ilike unaccent('%" . strtolower($request->filter) . "%')")
                    ->orWhereRaw("unaccent(lower(grupo)) ilike unaccent('%" . strtolower($request->filter) . "%')");
            }
            $dependencia =  $query->orderBy('id')
                                  ->paginate(10);
            return new DependenciaCollection($dependencia);

    }

    public function all(Request $request)
    {
        $dependencia = Dependencia::where('cientifica',true)->orderBy('nombre', 'ASC')
            ->get();

        return new DependenciaCollection($dependencia);

    }
    /**
     * @param Request $request
     * @return DependenciaCollection
     */
    public function allDelegaciones(Request $request)
    {
        $dependencia = Dependencia::where('cientifica',true)->orderBy('nombre', 'ASC')
            ->get();

        return new DependenciaCollection($dependencia);
    }
    /**
     * @param Request $request
     * @return DependenciaCollection
     */
    public function allIntervinientes(Request $request)
    {
        $dependencia = Dependencia::where('cientifica',false)->orderBy('nombre', 'ASC')
            ->get();

        return new DependenciaCollection($dependencia);
    }

    /**
     * @param DependenciaStoreRequest $request
     * @return DependenciaResource
     */
    public function store(DependenciaStoreRequest $request)
    {
        $this->validatePermission(PermissionConstant::CREAR_DEPENDENCIA);
        $request->validated();

        $dependencia = new Dependencia();
        $dependencia->cientifica = $request->dependencia['cientifica'];
        $dependencia->grupo = $request->dependencia['grupo'];
        $dependencia->nombre = $request->dependencia['nombre'];
        $dependencia->save();

        return new DependenciaResource($dependencia);
    }

    /**
     * @param Request $request
     * @param Dependencia $dependencia
     * @return DependenciaResource
     */
    public function show(Request $request, Dependencia $dependencia)
    {
        $this->validatePermission(PermissionConstant::CONSULTAR_DEPENDENCIA);
        return new DependenciaResource($dependencia);
    }

    /**
     * @param DependenciaUpdateRequest $request
     * @param Dependencia $dependencia
     * @return DependenciaResource
     */
    public function update(DependenciaUpdateRequest $request, Dependencia $dependencia)
    {
        $this->validatePermission(PermissionConstant::EDITAR_TRASLADO);
        $request->validated();
        $dependencia = Dependencia::find($request->dependencia['id']);
        $dependencia->cientifica = $request->dependencia['cientifica'];
        $dependencia->grupo = $request->dependencia['grupo'];
        $dependencia->nombre = $request->dependencia['nombre'];
        $dependencia->save();

        return new DependenciaResource($dependencia);
    }

    /**
     * @param Request $request
     * @param Dependencia $dependencia
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $this->validatePermission(PermissionConstant::ELIMINAR_DEPENDENCIA);
        $dependencia = Dependencia::find($id);
        $dependencia->delete();

        return response()->noContent();
    }
}
