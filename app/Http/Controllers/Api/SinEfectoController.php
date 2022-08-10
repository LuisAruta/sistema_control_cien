<?php

namespace App\Http\Controllers\Api;

use App\Constant\PermissionConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\OficioStoreRequest;
use App\Http\Requests\SinEfectoStoreRequest;
use App\Http\Requests\SinEfectoUpdateRequest;
use App\Http\Resources\SinEfectoResource;
use App\Http\Resources\SinEfectoCollection;
use App\Models\EstadoOficioHistorico;
use App\Models\Lugar;
use App\Models\Oficio;
use App\Models\SinEfecto;
use App\Traits\ValidationTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SinEfectoController extends Controller
{
    /**
     * @param Request $request
     * @return SinEfectoCollection
     */
    use ValidationTrait;

    public function index(Request $request)
    {
        $this->validatePermission(PermissionConstant::CONSULTAR_SIN_EFECTO);
        $query = SinEfecto::query();
        if (!$this->validateDependenciaUsuario()) {
            $user = Auth::user();
            $query->where('delegacion_dependencia_id', '=', $user->dependencia_id);
        }
        if ($request->filtros) {
            if ($request->filtros["fecha_desde_sol"] || $request->filtros["fecha_hasta_sol"]) {
                if($request->filtros["fecha_desde_sol"]&& $request->filtros["fecha_hasta_sol"]){
                    $hasta = Carbon::createFromFormat('Y-m-d', $request->filtros["fecha_hasta_sol"]);
                    $query->whereBetween('fecha_hora_solicitud', [$request->filtros["fecha_desde_sol"],$hasta]);
                } else if ($request->filtros["fecha_desde_sol"]){
                    $query->where('fecha_hora_solicitud', '>=',$request->filtros["fecha_desde_sol"]);
                } else {
                    $hasta = Carbon::createFromFormat('Y-m-d', $request->filtros["fecha_hasta_sol"]);
                    $query->where('fecha_hora_solicitud','<=', $hasta);
                }
            }
            if ($request->filtros["fecha_desde_reg"] || $request->filtros["fecha_hasta_reg"]) {
                if($request->filtros["fecha_desde_reg"]&& $request->filtros["fecha_hasta_reg"]){
                    $hasta = Carbon::createFromFormat('Y-m-d', $request->filtros["fecha_hasta_reg"]);
                    $query->whereBetween('fecha_hora_registro', [$request->filtros["fecha_desde_reg"],$hasta]);
                } else if ($request->filtros["fecha_desde_reg"]){
                    $query->where('fecha_hora_registro', '>=',$request->filtros["fecha_desde_reg"]);
                } else {
                    $hasta = Carbon::createFromFormat('Y-m-d', $request->filtros["fecha_hasta_reg"]);
                    $query->where('fecha_hora_registro','<=', $hasta);
                }
            }
            if ($request->filtros["departamento"]) {
                $query->whereHas('lugar', function ($query) use ($request) {
                    $query->whereRaw("unaccent(lower(departamento)) ilike unaccent('%".strtolower($request->filtros["departamento"])."%')");
                });
            }
            if ($request->filtros["nombre_usuario"]) {
                $query->whereHas('usuario', function ($query) use ($request) {
                    $query->whereRaw("unaccent(lower(nombre_completo)) ilike unaccent('%".strtolower($request->filtros["nombre_usuario"])."%')");
                });
            }
            if ($request->filtros["delegacion"]) {
                $query->where('delegacion_dependencia_id', $request->filtros["delegacion"]);
            }
            if ($request->filtros["datos_funcionario"]) {
                $query->whereRaw("unaccent(lower(funcionario)) ilike unaccent('%".strtolower($request->filtros["datos_funcionario"])."%')");
            }
            if ($request->filtros["descripcion"]) {
                $query->whereRaw("unaccent(lower(descripcion)) ilike unaccent('%".strtolower($request->filtros["descripcion"])."%')");
            }
        } else {
            $query->where('fecha_hora_registro', 'ilike', '%' . $request->filter . '%')
                ->orWhere('fecha_hora_solicitud', 'ilike', '%' . $request->filter . '%')
                ->orWhereHas('lugar', function ($query) use ($request) {
                    $query->whereRaw("unaccent(lower(departamento)) ilike unaccent('%" . strtolower($request->filter) . "%')");
                })
                ->orWhereHas('usuario', function ($query) use ($request) {
                    $query->whereRaw("unaccent(lower(nombre_completo)) ilike unaccent('%" . strtolower($request->filter) . "%')");
                })
                ->orWhereHas('delegacionDependencia', function ($query) use ($request) {
                    $query->whereRaw("unaccent(lower(nombre)) ilike unaccent('%" . strtolower($request->filter) . "%')");
                })
                ->orWhereRaw("unaccent(lower(funcionario)) ilike unaccent('%" . strtolower($request->filter) . "%')")
                ->orWhereRaw("unaccent(lower(descripcion)) ilike unaccent('%" . strtolower($request->filter) . "%')");
        }

        $sinEfectos = $query->orderBy('cantidad_oficios_pendientes', 'desc')->orderBy('numero_de_registro', 'desc')->paginate(10);
        return new SinEfectoCollection($sinEfectos);
    }

    /**
     * @param SinEfectoStoreRequest $request
     * @return SinEfectoResource
     */
    public function store(SinEfectoStoreRequest $request)
    {
        $this->validatePermission(PermissionConstant::CREAR_SIN_EFECTO);
        $request->validated();
        DB::transaction(function () use ($request){
            $lugar = new Lugar();
            $lugar->departamento = $request['lugar']['departamento'];
            $lugar->barrio = $request['lugar']['barrio'];
            $lugar->localidad = $request['lugar']['localidad'];
            $lugar->calle = $request['lugar']['calle'];
            $lugar->numero = $request['lugar']['numero'];
            $lugar->numero_departamento = $request['lugar']['numero_departamento'];
            $lugar->save();

            $user = Auth::user();
            $sinEfecto = new SinEfecto();
            $sinEfecto->lugar_id = $lugar->id;
            $sinEfecto->fecha_hora_registro = $request['sinefecto']['fecha_hora_registro'];
            $sinEfecto->fecha_hora_solicitud = $request['sinefecto']['fecha_hora_solicitud'];
            $sinEfecto->usuario_id = $user->id;
            $sinEfecto->delegacion_dependencia_id = $request['sinefecto']['delegacion_dependencia_id'];
            $sinEfecto->funcionario = $request['sinefecto']['funcionario'];
            $sinEfecto->descripcion = $request['sinefecto']['descripcion'];

            $numeroRegistro = SinEfecto::where('delegacion_dependencia_id', $request['sinefecto']['delegacion_dependencia_id'])->orderBy('numero_de_registro', 'desc')->first();
            $sinEfecto->numero_de_registro = $numeroRegistro ? $numeroRegistro->numero_de_registro + 1 : 1;
            $sinEfecto->cantidad_oficios_pendientes = 0;
            $sinEfecto->save();

            return new SinEfectoResource($sinEfecto);
        });
    }

    /**
     * @param Request $request
     * @param SinEfecto $sinEfecto
     * @return SinEfectoResource
     */
    public function show(Request $request, SinEfecto $sinEfecto)
    {
        $this->validatePermission(PermissionConstant::CONSULTAR_SIN_EFECTO);
        return new SinEfectoResource($sinEfecto);
    }

    /**
     * @param SinEfectoUpdateRequest $request
     * @param SinEfecto $sinEfecto
     * @return SinEfectoResource
     */
    public function update(SinEfectoUpdateRequest $request, $id)
    {
        $this->validatePermission(PermissionConstant::EDITAR_SIN_EFECTO);
        $request->validated();
        DB::transaction(function () use ($request){
            $sinEfecto = SinEfecto::findOrFail($request['sinefecto']['id']);
            $lugar = Lugar::findOrFail($sinEfecto->lugar_id);
            $lugar->departamento = $request['lugar']['departamento'];
            $lugar->barrio = $request['lugar']['barrio'];
            $lugar->localidad = $request['lugar']['localidad'];
            $lugar->calle = $request['lugar']['calle'];
            $lugar->numero = $request['lugar']['numero'];
            $lugar->numero_departamento = $request['lugar']['numero_departamento'];
            $lugar->save();
            $sinEfecto->lugar_id = $lugar->id;
            $sinEfecto->fecha_hora_registro = $request['sinefecto']['fecha_hora_registro'];
            $sinEfecto->fecha_hora_solicitud = $request['sinefecto']['fecha_hora_solicitud'];
            $sinEfecto->delegacion_dependencia_id = $request['sinefecto']['delegacion_dependencia_id'];
            $sinEfecto->funcionario = $request['sinefecto']['funcionario'];
            $sinEfecto->descripcion = $request['sinefecto']['descripcion'];
            $sinEfecto->numero_de_registro = $request['sinefecto']['numero_de_registro'];
            $sinEfecto->save();

            return new SinEfectoResource($sinEfecto);
        });
    }

    public function agregarOficio(OficioStoreRequest $request){
        $this->validatePermission(PermissionConstant::CREAR_OFICIO);
        $request->validated();

        DB::transaction(function () use ($request) {
            $oficio = new Oficio;
            $oficio->fecha_ingreso = $request['oficio']['fecha_ingreso'];
            $oficio->instancia_solicitante_id = $request['oficio']['instancia_solicitante_id'];
            $oficio->observacion = $request['oficio']['observacion'];
            $oficio->estado_oficio_id = $request['oficio']['estado_oficio_id'];

            $sinEfecto = SinEfecto::find($request['oficio']['oficiable_id']);
            $sinEfecto->oficios()->save($oficio);

            $user = Auth::user();
            $oficioHistorico = new EstadoOficioHistorico();
            $oficioHistorico->oficio_id = $oficio->id;
            $oficioHistorico->estado_oficio_id = $request['oficio']['estado_oficio_id'];
            $oficioHistorico->users_id = $user->id;
            $oficioHistorico->save();

            if($oficio->estado_oficio_id === 1){
                $sinEfecto->cantidad_oficios_pendientes = $sinEfecto->cantidad_oficios_pendientes + 1;
            }

            $sinEfecto->save();
        });
    }
    /**
     * @param Request $request
     * @param SinEfecto $sinEfecto
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $this->validatePermission(PermissionConstant::ELIMINAR_SIN_EFECTO);
        DB::transaction(function () use ($id) {
            $sinEfecto = SinEfecto::findOrFail($id);
            $sinEfecto->delete();
        });

        return response()->noContent();
    }
}
