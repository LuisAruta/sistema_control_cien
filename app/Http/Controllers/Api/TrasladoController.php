<?php

namespace App\Http\Controllers\Api;

use App\Constant\PermissionConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\OficioStoreRequest;
use App\Http\Requests\TrasladoStoreRequest;
use App\Http\Requests\TrasladoUpdateRequest;
use App\Http\Resources\TrasladoCollection;
use App\Http\Resources\TrasladoResource;
use App\Models\Documento;
use App\Models\EstadoOficioHistorico;
use App\Models\Expediente;
use App\Models\Lugar;
use App\Models\Oficio;
use App\Models\Traslado;
use App\Traits\ValidationTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TrasladoController extends Controller
{
    /**
     * @param Request $request
     * @return TrasladoCollection
     */
    use ValidationTrait;

    public function index(Request $request)
    {
        $this->validatePermission(PermissionConstant::CONSULTAR_TRASLADO);
        $query = Traslado::query();
        if (!$this->validateDependenciaUsuario()) {
            $user = Auth::user();
            $query->where('delegacion_dependencia_id', '=', $user->dependencia_id);
        }
        if ($request->filtros) {
            if ($request->filtros["fecha_desde"] || $request->filtros["fecha_hasta"]) {
                if($request->filtros["fecha_desde"]&& $request->filtros["fecha_hasta"]){
                    $hasta = Carbon::createFromFormat('Y-m-d', $request->filtros["fecha_hasta"]);
                    $query->whereBetween('fecha_hora', [$request->filtros["fecha_desde"],$hasta]);
                } else if ($request->filtros["fecha_desde"]){
                    $query->where('fecha_hora', '>=',$request->filtros["fecha_desde"]);
                } else {
                    $hasta = Carbon::createFromFormat('Y-m-d', $request->filtros["fecha_hasta"]);
                    $query->where('fecha_hora','<=', $hasta);
                }
            }
            if ($request->filtros["expediente"]) {
                $query->whereHas('expediente', function ($query) use ($request) {
                    $query->whereRaw("unaccent(lower(numero_expediente)) ilike unaccent('%".strtolower($request->filtros["expediente"])."%')");
                });
            }
            if ($request->filtros["departamento"]) {
                $query->whereHas('lugar', function ($query) use ($request) {
                    $query->whereRaw("unaccent(lower(departamento)) ilike unaccent('%".strtolower($request->filtros["departamento"])."%')");
                });
            }
            if ($request->filtros["legajoCMF"]) {
                $query->whereRaw("unaccent(lower(legajo_cuerpo_medico_forense)) ilike unaccent('%".strtolower($request->filtros["legajoCMF"])."%')");
            }
            if ($request->filtros["delegacion"]) {
                $query->where('delegacion_dependencia_id', $request->filtros["delegacion"]);
            }
            if ($request->filtros["interviniente"]) {
                $query->where('interviniento_dependencia_id', $request->filtros["interviniente"]);
            }
            if ($request->filtros["nombre_cadaver"]) {
                $query->whereRaw("unaccent(lower(cadaver_nombre)) ilike unaccent('%".strtolower($request->filtros["nombre_cadaver"])."%')");
            }
            if ($request->filtros["dni_cadaver"]) {
                $query->whereHas('cadaverDocumento', function ($query) use ($request) {
                    $query->whereRaw("unaccent(lower(numero)) ilike unaccent('%".strtolower($request->filtros["dni_cadaver"])."%')");
                });
            }
        } else {
            $query->where('fecha_hora', 'ilike', '%' . $request->filter . '%')
                ->orWhereHas('expediente', function ($query) use ($request) {
                    $query->whereRaw("unaccent(lower(numero_expediente)) ilike unaccent('%".strtolower($request->filter)."%')");
                })
                ->orWhereRaw("unaccent(lower(legajo_cuerpo_medico_forense)) ilike unaccent('%".strtolower($request->filter)."%')")
                ->orWhereHas('lugar', function ($query) use ($request) {
                    $query->whereRaw("unaccent(lower(departamento)) ilike unaccent('%".strtolower($request->filter)."%')");
                })
                ->orWhereHas('delegacionDependencia', function ($query) use ($request) {
                    $query->whereRaw("unaccent(lower(nombre)) ilike unaccent('%".strtolower($request->filter)."%')");
                })
                ->orWhereHas('intervinientoDependencia', function ($query) use ($request) {
                    $query->whereRaw("unaccent(lower(nombre)) ilike unaccent('%".strtolower($request->filter)."%')");
                })
                ->orWhereRaw("unaccent(lower(cadaver_nombre)) ilike unaccent('%".strtolower($request->filter)."%')")
                ->orWhereHas('cadaverDocumento', function ($query) use ($request) {
                    $query->whereRaw("unaccent(lower(numero)) ilike unaccent('%".strtolower($request->filter)."%')");
                })
                ->orWhereRaw("unaccent(lower(observaciones)) ilike unaccent('%".strtolower($request->filter)."%')");
        }

        $traslados = $query->orderBy('cantidad_oficios_pendientes', 'desc')->orderBy('id', 'desc')->paginate(10);
        return new TrasladoCollection($traslados);
    }

    /**
     * @param TrasladoStoreRequest $request
     * @return TrasladoResource
     */
    public function store(TrasladoStoreRequest $request)
    {
        $this->validatePermission(PermissionConstant::CREAR_TRASLADO);
        $request->validated();
        DB::transaction(function () use ($request){
        $documento_id = null;
        $expediente_id = null;
        $lugar = new Lugar();
        $lugar->departamento = $request['lugar']['departamento'];
        $lugar->barrio = $request['lugar']['barrio'];
        $lugar->localidad = $request['lugar']['localidad'];
        $lugar->calle = $request['lugar']['calle'];
        $lugar->numero = $request['lugar']['numero'];
        $lugar->numero_departamento = $request['lugar']['numero_departamento'];
        $lugar->save();
        if ($request['cadaver']['identificado'] === true) {
            $documento = Documento::where('tipo_documento', $request['cadaver']['cadaver_documento']['tipo_documento'])
                ->where('numero', $request['cadaver']['cadaver_documento']['numero'])
                ->first();
            if ($documento){
                $documento_id = $documento->id;
            } else {
                $doc = new Documento();
                $doc->tipo_documento = $request['cadaver']['cadaver_documento']['tipo_documento'];
                $doc->numero = $request['cadaver']['cadaver_documento']['numero'];
                $doc->save();
                $documento_id = $doc->id;
            }
        }
        if ($request['expediente']['numero_expediente']) {
            $expediente = new Expediente();
            $expediente->numero_expediente = $request['expediente']['numero_expediente'];
            $expediente->caratula = $request['expediente']['caratula'];
            $expediente->origen = $request['expediente']['origen'];
            $expediente->save();
            $expediente_id = $expediente->id;
        }
        $traslado = new Traslado();
        $traslado->lugar_id = $lugar->id;
        $traslado->cadaver_documento_id = $documento_id;
        $traslado->fecha_hora = $request['traslado']['fecha_hora'];
        $traslado->expediente_id = $expediente_id;
        $traslado->perito_usuario_id = $request['traslado']['perito_usuario_id'];
        $traslado->acompanante_usuario_id = $request['traslado']['acompanante_usuario_id'];
        $traslado->delegacion_dependencia_id = $request['traslado']['delegacion_dependencia_id'];
        $traslado->interviniento_dependencia_id = $request['traslado']['interviniento_dependencia_id'];
        $traslado->cadaver_nombre = $request['cadaver']['cadaver_nombre'];
        $traslado->observaciones = $request['cadaver']['observaciones'];
        $traslado->legajo_cuerpo_medico_forense = $request['traslado']['legajo_cuerpo_medico_forense'];
        $traslado->foto = $request['cadaver']['foto'];
        $traslado->identificado = $request['cadaver']['identificado'];

        $numeroRegistro = Traslado::where('delegacion_dependencia_id', $request['traslado']['delegacion_dependencia_id'])->orderBy('numero_de_registro', 'desc')->first();
        $traslado->numero_de_registro = $numeroRegistro ? $numeroRegistro->numero_de_registro + 1 : 1;

        $traslado->cantidad_oficios_pendientes = 0;
        $traslado->save();

        return new TrasladoResource($traslado);
        });

    }

    /**
     * @param Request $request
     * @param Traslado $traslado
     * @return TrasladoResource
     */
    public function show(Request $request, Traslado $traslado)
    {
        $this->validatePermission(PermissionConstant::CONSULTAR_TRASLADO);
        return new TrasladoResource($traslado);
    }

    /**
     * @param TrasladoUpdateRequest $request
     * @param Traslado $traslado
     * @return TrasladoResource
     */
    public function update(TrasladoUpdateRequest $request)
    {
        $this->validatePermission(PermissionConstant::EDITAR_TRASLADO);
        $request->validated();
        DB::transaction(function () use ($request) {
            $documento_id = null;
            $expediente_id = null;
            $traslado = Traslado::findOrFail($request['traslado']['id']);
            $lugar = Lugar::findOrFail($traslado->lugar_id);
            $lugar->departamento = $request['lugar']['departamento'];
            $lugar->barrio = $request['lugar']['barrio'];
            $lugar->localidad = $request['lugar']['localidad'];
            $lugar->calle = $request['lugar']['calle'];
            $lugar->numero = $request['lugar']['numero'];
            $lugar->numero_departamento = $request['lugar']['numero_departamento'];
            $lugar->save();
            // en caso de que se identifique los datos del cadaver
            if ($request['cadaver']['identificado'] && !$traslado->identificado) {
                $documento = Documento::where('tipo_documento', $request['cadaver']['cadaver_documento']['tipo_documento'])
                    ->where('numero', $request['cadaver']['cadaver_documento']['numero'])
                    ->first();
                if ($documento){
                    $documento_id = $documento->id;
                } else {
                    $doc = new Documento();
                    $doc->tipo_documento = $request['cadaver']['cadaver_documento']['tipo_documento'];
                    $doc->numero = $request['cadaver']['cadaver_documento']['numero'];
                    $doc->save();
                    $documento_id = $doc->id;
                }
            }
            if ($request['cadaver']['identificado'] && $traslado->identificado) {
                $documento = Documento::findOrFail($request['cadaver']['cadaver_documento']['id']);
                if($documento->tipo_documento != $request['cadaver_documento']['tipo_documento'] || $documento->numero != $request['cadaver']['cadaver_documento']['numero']){
                    $doc = Documento::where('tipo_documento', $request['cadaver']['cadaver_documento']['tipo_documento'])
                        ->where('numero', $request['cadaver']['cadaver_documento']['numero'])
                        ->first();
                    if ($doc){
                        $documento_id = $doc->id;
                    } else {
                        $doc = new Documento();
                        $doc->tipo_documento = $request['cadaver']['cadaver_documento']['tipo_documento'];
                        $doc->numero = $request['cadaver']['cadaver_documento']['numero'];
                        $doc->save();
                        $documento_id = $doc->id;
                    }
                }
            }
            if (!$request['cadaver']['identificado'] && $traslado->identificado) {
                $traslado->cadaver_documento_id = null;
                /*$documento = Documento::findOrFail($traslado->cadaver_documento_id);
                $documento->delete();*/
            }
            if ($request['expediente']['numero_expediente']) {
                if($traslado->expediente_id){
                    $expediente = Expediente::findOrFail($traslado->expediente_id);
                } else {
                    $expediente = new Expediente();
                }
                $expediente->numero_expediente = $request['expediente']['numero_expediente'];
                $expediente->caratula = $request['expediente']['caratula'];
                $expediente->origen = $request['expediente']['origen'];
                $expediente->save();
                $expediente_id = $expediente->id;
            }
            $traslado->lugar_id = $lugar->id;
            $traslado->identificado = $request['cadaver']['identificado'];
            $traslado->cadaver_documento_id = $documento_id;
            $traslado->fecha_hora = $request['traslado']['fecha_hora'];
            $traslado->expediente_id = $expediente_id;
            $traslado->perito_usuario_id = $request['traslado']['perito_usuario_id'];
            $traslado->acompanante_usuario_id = $request['traslado']['acompanante_usuario_id'];
            $traslado->delegacion_dependencia_id = $request['traslado']['delegacion_dependencia_id'];
            $traslado->interviniento_dependencia_id = $request['traslado']['interviniento_dependencia_id'];
            $traslado->cadaver_nombre = $request['cadaver']['cadaver_nombre'];
            $traslado->observaciones = $request['cadaver']['observaciones'];
            $traslado->legajo_cuerpo_medico_forense = $request['traslado']['legajo_cuerpo_medico_forense'];
            $traslado->foto = $request['cadaver']['foto'];
            $traslado->numero_de_registro = $request['traslado']['numero_de_registro'];
            $traslado->save();

            return new TrasladoResource($traslado);
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

            $traslado = Traslado::find($request['oficio']['oficiable_id']);
            $traslado->oficios()->save($oficio);

            $user = Auth::user();
            $oficioHistorico = new EstadoOficioHistorico();
            $oficioHistorico->oficio_id = $oficio->id;
            $oficioHistorico->estado_oficio_id = $request['oficio']['estado_oficio_id'];
            $oficioHistorico->users_id = $user->id;
            $oficioHistorico->save();

            if($oficio->estado_oficio_id === 1) {
                $traslado->cantidad_oficios_pendientes = $traslado->cantidad_oficios_pendientes + 1;
            }
            $traslado->save();
        });
    }

    /**
     * @param Request $request
     * @param Traslado $traslado
     * @return Response
     */
    public function destroy(Request $request, Traslado $traslado)
    {
        $this->validatePermission(PermissionConstant::ELIMINAR_TRASLADO);
        DB::transaction(function () use ($traslado) {
            $traslado->delete();
        });

        return response()->noContent();
    }
}
