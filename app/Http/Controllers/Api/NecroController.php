<?php

namespace App\Http\Controllers\Api;

use App\Constant\PermissionConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\NecroStoreRequest;
use App\Http\Requests\NecroUpdateRequest;
use App\Http\Requests\OficioStoreRequest;
use App\Http\Resources\NecroCollection;
use App\Http\Resources\NecroResource;
use App\Models\Documento;
use App\Models\EstadoOficioHistorico;
use App\Models\Expediente;
use App\Models\IntervencionTecnica;
use App\Models\Lugar;
use App\Models\Necro;
use App\Models\Oficio;
use App\Traits\ValidationTrait;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class NecroController extends Controller
{
    /**
     * @param Request $request
     * @return NecroCollection
     */
    use ValidationTrait;

    public function index(Request $request)
    {
        $this->validatePermission(PermissionConstant::CONSULTAR_NECRO);
        $query = Necro::query();
        if (!$this->validateDependenciaUsuario()) {
            $user = Auth::user();
            $query->where('delegacion_dependencia_id', '=', $user->dependencia_id);
        }
        if ($request->entre_fechas){
            switch ($request['entre_fechas']){
                case 1:
                    $query->where('estado_id', 1)
                        ->whereBetween('fecha_hora', [Carbon::yesterday(),Carbon::now()]);
                    break;
                case 2:
                    $desde = Carbon::now()->subDays(3);
                    $query->where('estado_id', 1)
                        ->whereBetween('fecha_hora', [$desde,Carbon::yesterday()]);
                    $query->where('fecha_hora', 'ilike', '%' . $request->filter . '%');
                    break;
                case 3:
                    $hasta = Carbon::now()->subDays(3);
                    $query->where('estado_id', 1)
                        ->where('fecha_hora', '<', $hasta);
                    break;
            }
        } else {
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
                if ($request->filtros["estado"]) {
                    $query->where('estado_id', $request->filtros["estado"]);
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
                    ->orWhereHas('estado', function ($query) use ($request) {
                        $query->whereRaw("unaccent(lower(nombre)) ilike unaccent('%".strtolower($request->filter)."%')");
                    })
                    ->orWhereRaw("unaccent(lower(observaciones)) ilike unaccent('%".strtolower($request->filter)."%')");
            }
        }
        $necros = $query->orderBy('cantidad_oficios_pendientes', 'desc')->orderBy('id', 'desc')->paginate(10);
        return new NecroCollection($necros);
    }

    /**
     * @param NecroStoreRequest $request
     * @return NecroResource
     */
    public function store(NecroStoreRequest $request)
    {
        $this->validatePermission(PermissionConstant::CREAR_NECRO);
        $request->validated();
        DB::transaction(function () use ($request) {
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
        $necro = new Necro();
        $necro->lugar_id = $lugar->id;
        $necro->cadaver_documento_id = $documento_id;
        $necro->fecha_hora = $request['necro']['fecha_hora'];
        $necro->expediente_id = $expediente_id;
        $necro->perito_usuario_id = $request['necro']['perito_usuario_id'];
        $necro->delegacion_dependencia_id = $request['necro']['delegacion_dependencia_id'];
        $necro->interviniento_dependencia_id = $request['necro']['interviniento_dependencia_id'];
        $necro->cadaver_nombre = $request['cadaver']['cadaver_nombre'];
        $necro->observaciones = $request['cadaver']['observaciones'];
        $necro->legajo_cuerpo_medico_forense = $request['necro']['legajo_cuerpo_medico_forense'];
        $necro->foto = $request['cadaver']['foto'];
        $necro->identificado = $request['cadaver']['identificado'];
        $necro->estado_id = 1;

        $numeroRegistro = Necro::where('delegacion_dependencia_id', $request['necro']['delegacion_dependencia_id'])->orderBy('numero_de_registro', 'desc')->first();
        $necro->numero_de_registro = $numeroRegistro ? $numeroRegistro->numero_de_registro + 1 : 1;
        $necro->cantidad_oficios_pendientes = 0;
        $necro->save();

        return new NecroResource($necro);
        });
    }

    /**
     * @param Request $request
     * @param Necro $necro
     * @return NecroResource
     */
    public function show(Request $request, Necro $necro)
    {
        $this->validatePermission(PermissionConstant::CONSULTAR_NECRO);
        return new NecroResource($necro);
    }

    /**
     * @param NecroUpdateRequest $request
     * @param Necro $necro
     * @return NecroResource
     */
    public function update(NecroUpdateRequest $request)
    {
        $this->validatePermission(PermissionConstant::EDITAR_NECRO);
        $request->validated();
        DB::transaction(function () use ($request) {
        $documento_id = null;
        $expediente_id = null;
        $necro = Necro::findOrFail($request['necro']['id']);
        $lugar = Lugar::findOrFail($necro->lugar_id);
        $lugar->departamento = $request['lugar']['departamento'];
        $lugar->barrio = $request['lugar']['barrio'];
        $lugar->localidad = $request['lugar']['localidad'];
        $lugar->calle = $request['lugar']['calle'];
        $lugar->numero = $request['lugar']['numero'];
        $lugar->numero_departamento = $request['lugar']['numero_departamento'];
        $lugar->save();
        // en caso de que se identifique los datos del cadaver
        if ($request['cadaver']['identificado'] && !$necro->identificado) {
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
        if ($request['cadaver']['identificado'] && $necro->identificado) {
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
        if (!$request['cadaver']['identificado'] && $necro->identificado) {
            $necro->cadaver_documento_id = null;
            /*$documento = Documento::findOrFail($necro->cadaver_documento_id);
            $documento->delete();*/
        }
        if ($request['expediente']['numero_expediente']) {
            if($necro->expediente_id){
                $expediente = Expediente::findOrFail($necro->expediente_id);
            } else {
                $expediente = new Expediente();
            }
            $expediente->numero_expediente = $request['expediente']['numero_expediente'];
            $expediente->caratula = $request['expediente']['caratula'];
            $expediente->origen = $request['expediente']['origen'];
            $expediente->save();
            $expediente_id = $expediente->id;
        }
        $necro->lugar_id = $lugar->id;
        $necro->fecha_hora = $request['necro']['fecha_hora'];
        $necro->expediente_id = $expediente_id;
        $necro->perito_usuario_id = $request['necro']['perito_usuario_id'];
        $necro->delegacion_dependencia_id = $request['necro']['delegacion_dependencia_id'];
        $necro->interviniento_dependencia_id = $request['necro']['interviniento_dependencia_id'];
        $necro->cadaver_nombre = $request['cadaver']['cadaver_nombre'];
        $necro->cadaver_documento_id = $documento_id;
        $necro->observaciones = $request['cadaver']['observaciones'];
        $necro->legajo_cuerpo_medico_forense = $request['necro']['legajo_cuerpo_medico_forense'];
        $necro->foto = $request['cadaver']['foto'];
        $necro->estado_id = $request['necro']['estado_id'];
        $necro->numero_de_registro = $request['necro']['numero_de_registro'];
        $necro->identificado = $request['cadaver']['identificado'];
        $necro->save();
        //$this->borrarDocumento();
        return new NecroResource($necro);
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

            $necro = Necro::find($request['oficio']['oficiable_id']);
            $necro->estado_id = $request['oficio']['estado_id'];
            $necro->oficios()->save($oficio);

            $user = Auth::user();
            $oficioHistorico = new EstadoOficioHistorico();
            $oficioHistorico->oficio_id = $oficio->id;
            $oficioHistorico->estado_oficio_id = $request['oficio']['estado_oficio_id'];
            $oficioHistorico->users_id = $user->id;
            $oficioHistorico->save();

            if($oficio->estado_oficio_id === 1) {
                $necro->cantidad_oficios_pendientes = $necro->cantidad_oficios_pendientes + 1;
            }
            $necro->save();
        });
    }

    /**
     * @param Request $request
     * @param Necro $necro
     * @return Response
     */
    public function destroy(Request $request, Necro $necro)
    {
        $this->validatePermission(PermissionConstant::ELIMINAR_NECRO);
        DB::transaction(function () use ($necro) {
            $necro->delete();
            //$this->borrarDocumento();
        });
        return response()->noContent();
    }

    public function borrarDocumento(){
        $registros = DB::select(DB::raw("
              SELECT documento_id as id FROM users where deleted_at is null
                UNION
              SELECT documento_id as id  FROM victimas where deleted_at is null
                UNION
              SELECT documento_id as id  FROM detenidos where deleted_at is null
                UNION
              SELECT cadaver_documento_id as id FROM necros where deleted_at is null
                UNION
              SELECT cadaver_documento_id as id FROM traslados where deleted_at is null"));

        $documentosActivos = array();
        foreach ($registros as $reg) {
            $documentosActivos[] = $reg->id;
        }

        DB::table('documentos')->whereNotIn('id', $documentosActivos)
            ->delete();
    }
}
