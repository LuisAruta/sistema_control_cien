<?php

namespace App\Http\Controllers\Api;

use App\Constant\PermissionConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\OficioStoreRequest;
use App\Http\Requests\OficioUpdateRequest;
use App\Http\Resources\OficioCollection;
use App\Http\Resources\OficioResource;
use App\Models\EstadoOficioHistorico;
use App\Models\IntervencionTecnica;
use App\Models\Necro;
use App\Models\Oficio;
use App\Models\SinEfecto;
use App\Models\Traslado;
use App\Traits\ValidationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;
use Illuminate\Support\Facades\DB;

class OficioController extends Controller
{
    /**
     * @param Request $request
     * @return OficioCollection
     */
    use ValidationTrait;

    public function index(Request $request)
    {
        $oficios = Oficio::all();

        return new OficioCollection($oficios);
    }


    /**
     * @param OficioStoreRequest $request
     * @return OficioResource
     */
    public function store(OficioStoreRequest $request)
    {
        $this->validatePermission(PermissionConstant::CREAR_OFICIO);
        $request->validated();

        $oficio = Oficio::create($request->validated());

        return new OficioResource($oficio);
    }

    /**
     * @param Request $request
     * @param Oficio $oficio
     * @return OficioResource
     */
    public function show(Request $request, Oficio $oficio)
    {
        return new OficioResource($oficio);
    }

    /**
     * @param OficioUpdateRequest $request
     * @param Oficio $oficio
     * @return OficioResource
     */
    public function update(OficioUpdateRequest $request, $id)
    {
        $this->validatePermission(PermissionConstant::EDITAR_OFICIO);
        $request->validated();

        DB::transaction(function () use ($request){
            $oficio = Oficio::findOrFail($request['oficio']['id']);
            $oficio->fecha_ingreso = $request['oficio']['fecha_ingreso'];
            $oficio->instancia_solicitante_id = $request['oficio']['instancia_solicitante_id'];
            $oficio->observacion = $request['oficio']['observacion'];
            switch ($oficio->oficiable_type){
                case 'App\Models\IntervencionTecnica':
                    $informe = IntervencionTecnica::findOrFail($oficio->oficiable_id);
                    break;
                case 'App\Models\Necro':
                    $informe = Necro::findOrFail($oficio->oficiable_id);
                    break;
                case 'App\Models\Traslado':
                    $informe = Traslado::findOrFail($oficio->oficiable_id);
                    break;
                case 'App\Models\SinEfecto':
                    $informe = SinEfecto::findOrFail($oficio->oficiable_id);
                    break;
            }
            if($oficio->estado_oficio_id !== $request['oficio']['estado_oficio_id']) { //Este calculo es solo para ver si cambia o no el estado del oficio
                if($request['oficio']['estado_oficio_id'] === 1){
                    $informe->cantidad_oficios_pendientes = $informe->cantidad_oficios_pendientes + 1;
                } else if($oficio->estado_oficio_id === 1){
                    $informe->cantidad_oficios_pendientes = $informe->cantidad_oficios_pendientes - 1;
                }
            }
            $oficio->estado_oficio_id = $request['oficio']['estado_oficio_id'];
            $oficio->save();
            $informe->save();

            $user = Auth::user();
            $oficioHistorico = new EstadoOficioHistorico();
            $oficioHistorico->oficio_id = $request['oficio']['id'];
            $oficioHistorico->estado_oficio_id = $request['oficio']['estado_oficio_id'];
            $oficioHistorico->users_id = $user->id;
            $oficioHistorico->save();

            return new OficioResource($oficio);
        });
    }

    /**
     * @param Request $request
     * @param Oficio $oficio
     * @return Response
     */
    public function destroy(Request $request, Oficio $oficio)
    {
        $this->validatePermission(PermissionConstant::ELIMINAR_OFICIO);

        $oficio->delete();
        switch ($oficio->oficiable_type){
            case 'App\Models\IntervencionTecnica':
                $informe = IntervencionTecnica::findOrFail($oficio->oficiable_id);
                $informe->cantidad_oficios_pendientes = $informe->cantidad_oficios_pendientes - 1;
                $informe->save();
                break;
            case 'App\Models\Necro':
                $informe = Necro::findOrFail($oficio->oficiable_id);
                $informe->cantidad_oficios_pendientes = $informe->cantidad_oficios_pendientes - 1;
                $informe->save();
                break;
            case 'App\Models\Traslado':
                $informe = Traslado::findOrFail($oficio->oficiable_id);
                $informe->cantidad_oficios_pendientes = $informe->cantidad_oficios_pendientes - 1;
                $informe->save();
                break;
            case 'App\Models\SinEfecto':
                $informe = SinEfecto::findOrFail($oficio->oficiable_id);
                $informe->cantidad_oficios_pendientes = $informe->cantidad_oficios_pendientes - 1;
                $informe->save();
                break;
        }


        return response()->noContent();
    }

    public function findAllByIDOficiable($type, $id)
    {
        switch ($type){
            case 'intervencion':
                $type = 'App\Models\IntervencionTecnica';
                break;
            case 'necro':
                $type = 'App\Models\Necro';
                break;
            case 'traslado':
                $type = 'App\Models\Traslado';
                break;
            case 'sinefecto':
                $type = 'App\Models\SinEfecto';
                break;
        }

        $oficio = Oficio::query()
            ->where('oficiable_id','=',$id)
            ->where('oficiable_type', '=', $type)
            ->get();

        return new OficioCollection($oficio);
    }
}
