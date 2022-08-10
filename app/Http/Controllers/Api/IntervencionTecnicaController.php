<?php

namespace App\Http\Controllers\Api;

use App\Constant\PermissionConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\IntervencionTecnicaStoreRequest;
use App\Http\Requests\IntervencionTecnicaUpdateRequest;
use App\Http\Requests\OficioStoreRequest;
use App\Http\Resources\IntervencionTecnicaCollection;
use App\Http\Resources\IntervencionTecnicaResource;
use App\Models\Detenido;
use App\Models\Documento;
use App\Models\EstadoOficioHistorico;
use App\Models\Expediente;
use App\Models\Indicio;
use App\Models\InformesLaboratorio;
use App\Models\Lugar;
use App\Models\IntervencionTecnica;
use App\Models\Oficio;
use App\Models\Victima;
use App\Traits\ValidationTrait;
use Carbon\Carbon;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class IntervencionTecnicaController extends Controller
{
    /**
     * @param Request $request
     * @return IntervencionTecnicaCollection
     */
    use ValidationTrait;

    public function index(Request $request)
    {
        $this->validatePermission(PermissionConstant::CONSULTAR_INTERVENCION_TECNICA);
        $query = IntervencionTecnica::query();
        if (!$this->validateDependenciaUsuario()) {
            $user = Auth::user();
            $query->where('delegacion_dependencia_id', '=', $user->dependencia_id);
        }
        if ($request->entre_fechas) {
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
        }else {
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
                if ($request->filtros["delito"]) {
                    $query->where('delito_id', $request->filtros["delito"]);
                }
                if ($request->filtros["departamento"]) {
                    $query->whereHas('lugar', function ($query) use ($request) {
                        $query->whereRaw("unaccent(lower(departamento)) ilike unaccent('%".strtolower($request->filtros["departamento"])."%')");
                    });
                }
                if ($request->filtros["estado"]) {
                    $query->where('estado_id', $request->filtros["estado"]);
                }
                if ($request->filtros["delegacion"]) {
                    $query->where('delegacion_dependencia_id', $request->filtros["delegacion"]);
                }
                if ($request->filtros["interviniente"]) {
                    $query->where('interviniento_dependencia_id', $request->filtros["interviniente"]);
                }
                if ($request->filtros["tipo_intervencion"]) {
                    $query->where('tipo_intervencion_tecnica_id', $request->filtros["tipo_intervencion"]);
                }
            } else {
                $query->where('fecha_hora', 'ilike', '%' . $request->filter . '%')
                    ->orWhereHas('expediente', function ($query) use ($request) {
                        $query->whereRaw("unaccent(lower(numero_expediente)) ilike unaccent('%".strtolower($request->filter)."%')");
                    })
                    ->orWhereHas('delito', function ($query) use ($request) {
                        $query->whereRaw("unaccent(lower(nombre)) ilike unaccent('%".strtolower($request->filter)."%')");
                    })
                    ->orWhereHas('lugar', function ($query) use ($request) {
                        $query->whereRaw("unaccent(lower(departamento)) ilike unaccent('%".strtolower($request->filter)."%')");
                    })
                    ->orWhereHas('delegacionDependencia', function ($query) use ($request) {
                        $query->whereRaw("unaccent(lower(nombre)) ilike unaccent('%".strtolower($request->filter)."%')");
                    })
                    ->orWhereHas('intervinientoDependencia', function ($query) use ($request) {
                        $query->whereRaw("unaccent(lower(nombre)) ilike unaccent('%".strtolower($request->filter)."%')");
                    })
                    ->orWhereHas('estado', function ($query) use ($request) {
                        $query->whereRaw("unaccent(lower(nombre)) ilike unaccent('%".strtolower($request->filter)."%')");
                    })
                    ->orWhereRaw("unaccent(lower(descripcion)) ilike unaccent('%".strtolower($request->filter)."%')");
            }
        }
        $intervencionesTecnicas = $query->orderBy('cantidad_oficios_pendientes', 'desc')->orderBy('numero_de_registro', 'desc')->paginate(10);
        return new IntervencionTecnicaCollection($intervencionesTecnicas);
    }

    /**
     * @param IntervencionTecnicaStoreRequest $request
     * @return IntervencionTecnicaResource
     */
    public function store(IntervencionTecnicaStoreRequest $request)
    {
        $this->validatePermission(PermissionConstant::CREAR_INTERVENCION_TECNICA);
        $request->validated();
        DB::transaction(function () use ($request){
        $expediente_id = null;
        $lugar = new Lugar();
        $lugar->departamento = $request['lugar']['departamento'];
        $lugar->barrio = $request['lugar']['barrio'];
        $lugar->localidad = $request['lugar']['localidad'];
        $lugar->calle = $request['lugar']['calle'];
        $lugar->numero = $request['lugar']['numero'];
        $lugar->numero_departamento = $request['lugar']['numero_departamento'];
        $lugar->save();
        if ($request['expediente']['numero_expediente']) {
            $expediente = new Expediente();
            $expediente->numero_expediente = $request['expediente']['numero_expediente'];
            $expediente->caratula = $request['expediente']['caratula'];
            $expediente->origen = $request['expediente']['origen'];
            $expediente->save();
            $expediente_id = $expediente->id;
        }
        $intervenciontecnica = new IntervencionTecnica();
        $intervenciontecnica->lugar_id = $lugar->id;
        $intervenciontecnica->fecha_hora = $request['intervenciontecnica']['fecha_hora'];
        $intervenciontecnica->expediente_id = $expediente_id;
        $intervenciontecnica->descripcion = $request['intervenciontecnica']['descripcion'];
        $intervenciontecnica->perito_usuario_id = $request['intervenciontecnica']['perito_usuario_id'];
        $intervenciontecnica->tecnico_revelador_usuario_id = $request['intervenciontecnica']['tecnico_revelador_usuario_id'];
        if($request['intervenciontecnica']['secretario_usuario_id']){
            $intervenciontecnica->secretario_usuario_id = $request['intervenciontecnica']['secretario_usuario_id'];
        }
        $intervenciontecnica->foto = $request['intervenciontecnica']['foto'];
        if($intervenciontecnica->foto){
            $intervenciontecnica->fotografo_usuario_id = $request['intervenciontecnica']['fotografo_usuario_id'];
        }
        $intervenciontecnica->plano = $request['intervenciontecnica']['plano'];
        if($intervenciontecnica->plano){
            $intervenciontecnica->planimetrista_usuario_id = $request['intervenciontecnica']['planimetrista_usuario_id'];
        }
        $intervenciontecnica->video = $request['intervenciontecnica']['video'];
        if($intervenciontecnica->video){
            $intervenciontecnica->video_usuario_id = $request['intervenciontecnica']['video_usuario_id'];
        }
        $intervenciontecnica->delegacion_dependencia_id = $request['intervenciontecnica']['delegacion_dependencia_id'];
        $intervenciontecnica->interviniento_dependencia_id = $request['intervenciontecnica']['interviniento_dependencia_id'];
        $intervenciontecnica->tipo_intervencion_tecnica_id = $request['intervenciontecnica']['tipo_intervencion_tecnica_id'];
        if($intervenciontecnica->tipo_intervencion_tecnica_id === 2){
            $intervenciontecnica->tipo_colision_id = $request['intervenciontecnica']['tipo_colision_id'];
        }
        $intervenciontecnica->delito_id = $request['intervenciontecnica']['delito_id'];
        $intervenciontecnica->estado_id = 1;

        $numeroRegistro = IntervencionTecnica::where('delegacion_dependencia_id', $request['intervenciontecnica']['delegacion_dependencia_id'])->orderBy('numero_de_registro', 'desc')->first();
        $intervenciontecnica->numero_de_registro = $numeroRegistro ? $numeroRegistro->numero_de_registro + 1 : 1;
        $intervenciontecnica->cantidad_oficios_pendientes = 0;
        $intervenciontecnica->save();

        foreach($request['indicios'] as $indicioIte){
           $indicio = new Indicio;
           $indicio->tipo_indicio_id = $indicioIte['tipo_indicio_id'];
           $indicio->cantidad = $indicioIte['cantidad'];
           $indicio->detalle = $indicioIte['detalle'];
           $intervenciontecnica->indicios()->save($indicio);
        };

        foreach($request['informes_laboratorio'] as $informesLab){
            $informe = new InformesLaboratorio();
            $informe->tipo_informes_laboratorio_id = $informesLab['tipo_informes_laboratorio_id'];
            $informe->numero_informe = $informesLab['numero_informe'];
            $intervenciontecnica->informesLaboratorio()->save($informe);
        };

        foreach($request['detenidos'] as $detenidoArr){
            $detenido = new Detenido;
            $detenido->nombre = $detenidoArr['nombre'];
            $detenido->apellido = $detenidoArr['apellido'];

            $documento = Documento::where('tipo_documento', $detenidoArr['documento']['tipo_documento'])
                ->where('numero', $detenidoArr['documento']['numero'])
                ->first();
            if ($documento){
                $detenido->documento_id = $documento->id;
            } else {
                $doc = new Documento();
                $doc->tipo_documento = $detenidoArr['documento']['tipo_documento'];
                $doc->numero = $detenidoArr['documento']['numero'];
                $doc->save();
                $detenido->documento_id = $doc->id;
            }
            $detenido->fecha_nacimiento = $detenidoArr['fecha_nacimiento'];
            $detenido->save();

            $intervenciontecnica->detenidos()->save($detenido);
        };

        foreach($request['victimas'] as $victimaArr){
                $victima = new Victima;
                $victima->nombre = $victimaArr['nombre'];
                $victima->apellido = $victimaArr['apellido'];

                $documento = Documento::where('tipo_documento', $victimaArr['documento']['tipo_documento'])
                    ->where('numero', $victimaArr['documento']['numero'])
                    ->first();
                if ($documento){
                    $victima->documento_id = $documento->id;
                } else {
                    $doc = new Documento();
                    $doc->tipo_documento = $victimaArr['documento']['tipo_documento'];
                    $doc->numero = $victimaArr['documento']['numero'];
                    $doc->save();
                    $victima->documento_id = $doc->id;
                }
                $victima->fecha_nacimiento = $victimaArr['fecha_nacimiento'];
                $victima->save();

                $intervenciontecnica->victimas()->save($victima);
            };

        return new IntervencionTecnicaResource($intervenciontecnica);
        });
    }

    /**
     * @param Request $request
     * @param IntervencionTecnica $intervencionTecnica
     * @return IntervencionTecnicaResource
     */
    public function show(Request $request, IntervencionTecnica $intervencionTecnica)
    {
        $this->validatePermission(PermissionConstant::CONSULTAR_INTERVENCION_TECNICA);
        return new IntervencionTecnicaResource($intervencionTecnica);
    }

    /**
     * @param IntervencionTecnicaUpdateRequest $request
     * @param IntervencionTecnica $intervencionTecnica
     * @return IntervencionTecnicaResource
     */
    public function update(IntervencionTecnicaUpdateRequest $request, $id)
    {
        $this->validatePermission(PermissionConstant::EDITAR_INTERVENCION_TECNICA);
        $request->validated();
        DB::transaction(function () use ($request){
        $expediente_id = null;
        $intervenciontecnica = IntervencionTecnica::findOrFail($request['intervenciontecnica']['id']);
        $lugar = Lugar::findOrFail($intervenciontecnica->lugar_id);
        $lugar->departamento = $request['lugar']['departamento'];
        $lugar->barrio = $request['lugar']['barrio'];
        $lugar->localidad = $request['lugar']['localidad'];
        $lugar->calle = $request['lugar']['calle'];
        $lugar->numero = $request['lugar']['numero'];
        $lugar->numero_departamento = $request['lugar']['numero_departamento'];
        $lugar->save();
        if ($request['expediente']['numero_expediente']) {
            if($intervenciontecnica->expediente_id){
                $expediente = Expediente::findOrFail($intervenciontecnica->expediente_id);
            } else {
                $expediente = new Expediente();
            }
            $expediente->numero_expediente = $request['expediente']['numero_expediente'];
            $expediente->caratula = $request['expediente']['caratula'];
            $expediente->origen = $request['expediente']['origen'];
            $expediente->save();
            $expediente_id = $expediente->id;
        }
        $intervenciontecnica->lugar_id = $lugar->id;
        $intervenciontecnica->fecha_hora = $request['intervenciontecnica']['fecha_hora'];
        $intervenciontecnica->expediente_id = $expediente_id;
        $intervenciontecnica->descripcion = $request['intervenciontecnica']['descripcion'];
        $intervenciontecnica->perito_usuario_id = $request['intervenciontecnica']['perito_usuario_id'];
        $intervenciontecnica->tecnico_revelador_usuario_id = $request['intervenciontecnica']['tecnico_revelador_usuario_id'];
        if($request['intervenciontecnica']['secretario_usuario_id']){
            $intervenciontecnica->secretario_usuario_id = $request['intervenciontecnica']['secretario_usuario_id'];
        }
        $intervenciontecnica->foto = $request['intervenciontecnica']['foto'];
        $intervenciontecnica->fotografo_usuario_id = null;
        if($intervenciontecnica->foto){
            $intervenciontecnica->fotografo_usuario_id = $request['intervenciontecnica']['fotografo_usuario_id'];
        }
        $intervenciontecnica->plano = $request['intervenciontecnica']['plano'];
        $intervenciontecnica->planimetrista_usuario_id = null;
        if($intervenciontecnica->plano){
            $intervenciontecnica->planimetrista_usuario_id = $request['intervenciontecnica']['planimetrista_usuario_id'];
        }
        $intervenciontecnica->video = $request['intervenciontecnica']['video'];
        $intervenciontecnica->video_usuario_id = null;
        if($intervenciontecnica->video){
            $intervenciontecnica->video_usuario_id = $request['intervenciontecnica']['video_usuario_id'];
        }
        $intervenciontecnica->delegacion_dependencia_id = $request['intervenciontecnica']['delegacion_dependencia_id'];
        $intervenciontecnica->interviniento_dependencia_id = $request['intervenciontecnica']['interviniento_dependencia_id'];
        $intervenciontecnica->delito_id = $request['intervenciontecnica']['delito_id'];
        $intervenciontecnica->estado_id = $request['intervenciontecnica']['estado_id'];
        $intervenciontecnica->tipo_intervencion_tecnica_id = $request['intervenciontecnica']['tipo_intervencion_tecnica_id'];
        if($intervenciontecnica->tipo_intervencion_tecnica_id === 2){
            $intervenciontecnica->tipo_colision_id = $request['intervenciontecnica']['tipo_colision_id'];
        } else {
            $intervenciontecnica->tipo_colision_id = null;
        }
        $intervenciontecnica->numero_de_registro = $request['intervenciontecnica']['numero_de_registro'];

        $intervenciontecnica->save();

        $existingIndicios = [];
        foreach($request['indicios'] as $indicioIte){
            if (!$indicioIte['id']) {
                $indicio = new Indicio;
                $indicio->tipo_indicio_id = $indicioIte['tipo_indicio_id'];
                $indicio->cantidad = $indicioIte['cantidad'];
                $indicio->detalle = $indicioIte['detalle'];
                $intervenciontecnica->indicios()->save($indicio);
                $existingIndicios[] = $indicio->id;
            } else{
                $indicio = Indicio::findOrFail($indicioIte['id']);
                $indicio->tipo_indicio_id = $indicioIte['tipo_indicio_id'];
                $indicio->cantidad = $indicioIte['cantidad'];
                $indicio->detalle = $indicioIte['detalle'];
                $indicio->save();
                $existingIndicios[] = $indicioIte['id'];
            }
        }

        Indicio::whereNotIn('id', $existingIndicios)->delete();

        $existingInformesLab = [];
        foreach($request['informes_laboratorio'] as $informesLab){
            if (!$informesLab['id']) {
                $informe = new InformesLaboratorio();
                $informe->tipo_informes_laboratorio_id = $informesLab['tipo_informes_laboratorio_id'];
                $informe->numero_informe = $informesLab['numero_informe'];
                $intervenciontecnica->informesLaboratorio()->save($informe);
                $existingInformesLab[] = $informe->id;
            } else {
                $informe = InformesLaboratorio::findOrFail($informesLab['id']);
                $informe->tipo_informes_laboratorio_id = $informesLab['tipo_informes_laboratorio_id'];
                $informe->numero_informe = $informesLab['numero_informe'];
                $informe->save();
                $existingInformesLab[] = $informesLab['id'];
            }
        }

        InformesLaboratorio::whereNotIn('id', $existingInformesLab)->delete();

        $existingDetenidos = [];
        foreach($request['detenidos'] as $detenidoIte){
                if (!$detenidoIte['id']) {
                   $detenido = new Detenido;
                   $detenido->nombre = $detenidoIte['nombre'];
                   $detenido->apellido = $detenidoIte['apellido'];

                   $documento = Documento::where('tipo_documento', $detenidoIte['documento']['tipo_documento'])
                            ->where('numero', $detenidoIte['documento']['numero'])
                            ->first();
                   if ($documento){
                        $detenido->documento_id = $documento->id;
                   } else {
                        $doc = new Documento();
                        $doc->tipo_documento = $detenidoIte['documento']['tipo_documento'];
                        $doc->numero = $detenidoIte['documento']['numero'];
                        $doc->save();
                        $detenido->documento_id = $doc->id;
                   }
                   $detenido->fecha_nacimiento = $detenidoIte['fecha_nacimiento'];
                   $detenido->save();

                   $intervenciontecnica->detenidos()->save($detenido);
                   $existingDetenidos[] = $detenido->id;
                } else {
                    $detenido = Detenido::findOrFail($detenidoIte['id']);
                    $detenido->nombre = $detenidoIte['nombre'];
                    $detenido->apellido = $detenidoIte['apellido'];

                    $documento = Documento::where('tipo_documento', $detenidoIte['documento']['tipo_documento'])
                        ->where('numero', $detenidoIte['documento']['numero'])
                        ->first();

                    if (!$documento || $detenido->documento->tipo_documento !== $documento->tipo_documento || $detenido->documento->numero !== $documento->numero ) {
                       $doc = new Documento();
                       $doc->tipo_documento = $detenidoIte['documento']['tipo_documento'];
                       $doc->numero = $detenidoIte['documento']['numero'];
                       $doc->save();
                       $detenido->documento_id = $doc->id;
                    }
                    $detenido->fecha_nacimiento = $detenidoIte['fecha_nacimiento'];
                    $detenido->save();
                    $existingDetenidos[] = $detenidoIte['id'];
                }
            }
        Detenido::whereNotIn('id', $existingDetenidos)->delete();

        $existingVictimas = [];
            foreach($request['victimas'] as $victimaIte){
                if (!$victimaIte['id']) {
                    $victima = new Victima;
                    $victima->nombre = $victimaIte['nombre'];
                    $victima->apellido = $victimaIte['apellido'];

                    $documento = Documento::where('tipo_documento', $victimaIte['documento']['tipo_documento'])
                        ->where('numero', $victimaIte['documento']['numero'])
                        ->first();
                    if ($documento){
                        $victima->documento_id = $documento->id;
                    } else {
                        $doc = new Documento();
                        $doc->tipo_documento = $victimaIte['documento']['tipo_documento'];
                        $doc->numero = $victimaIte['documento']['numero'];
                        $doc->save();
                        $victima->documento_id = $doc->id;
                    }
                    $victima->fecha_nacimiento = $victimaIte['fecha_nacimiento'];
                    $victima->save();

                    $intervenciontecnica->victimas()->save($victima);
                    $existingVictimas[] = $victima->id;
                } else {
                    $victima = Victima::findOrFail($victimaIte['id']);
                    $victima->nombre = $victimaIte['nombre'];
                    $victima->apellido = $victimaIte['apellido'];

                    $documento = Documento::where('tipo_documento', $victimaIte['documento']['tipo_documento'])
                        ->where('numero', $victimaIte['documento']['numero'])
                        ->first();

                    if (!$documento || $victima->documento->tipo_documento !== $documento->tipo_documento || $victima->documento->numero !== $documento->numero ) {
                        $doc = new Documento();
                        $doc->tipo_documento = $victimaIte['documento']['tipo_documento'];
                        $doc->numero = $victimaIte['documento']['numero'];
                        $doc->save();
                        $victima->documento_id = $doc->id;
                    }
                    $victima->fecha_nacimiento = $victimaIte['fecha_nacimiento'];
                    $victima->save();
                    $existingVictimas[] = $victimaIte['id'];
                }
            }
        Victima::whereNotIn('id', $existingVictimas)->delete();
        //$this->borrarDocumento();
        return new IntervencionTecnicaResource($intervenciontecnica);
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

            $intervencion = IntervencionTecnica::find($request['oficio']['oficiable_id']);
            $intervencion->estado_id = $request['oficio']['estado_id'];
            $intervencion->oficios()->save($oficio);

            $user = Auth::user();
            $oficioHistorico = new EstadoOficioHistorico();
            $oficioHistorico->oficio_id = $oficio->id;
            $oficioHistorico->estado_oficio_id = $request['oficio']['estado_oficio_id'];
            $oficioHistorico->users_id = $user->id;
            $oficioHistorico->save();

            if($oficio->estado_oficio_id === 1){
              $intervencion->cantidad_oficios_pendientes =  $intervencion->cantidad_oficios_pendientes + 1;
            }

            $intervencion->save();
        });
    }
    /**
     * @param Request $request
     * @param IntervencionTecnica $intervencionTecnica
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $this->validatePermission(PermissionConstant::ELIMINAR_INTERVENCION_TECNICA);
        DB::transaction(function () use ($id) {
            $intervencionTecnica = IntervencionTecnica::findOrFail($id);
            $intervencionTecnica->delete();
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
