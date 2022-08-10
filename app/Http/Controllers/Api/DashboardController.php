<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delito;
use App\Models\Dependencia;
use App\Models\Estado;
use App\Models\EstadoOficio;
use App\Models\Necro;
use App\Models\IntervencionTecnica;
use App\Models\SiniestroVial;
use App\Models\TipoColision;
use App\Models\TipoIndicio;
use App\Models\TipoInformesLaboratorio;
use App\Models\TipoIntervencionTecnica;
use App\Models\Traslado;
use App\Models\User;
use App\Traits\ValidationTrait;
use Auth;
use Carbon\Carbon;
use Httpful\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    use ValidationTrait;

    public function calcularEstadoActual($delegacion_id)
    {
        if (!$this->validateDependenciaUsuario()) {
            $user = Auth::user();
            $delegacion_id = $user->dependencia_id;
        }
        $estadisticas['nombre'] = 'RC';
        $estadisticas['ruta'] = 'intervenciontecnica';
        $estadisticas['numero_24'] = $this->buscarXFechas(IntervencionTecnica::query(), 1, null, $delegacion_id, 'rc');
        $estadisticas['numero_48'] = $this->buscarXFechas(IntervencionTecnica::query(), 3, 1, $delegacion_id, 'rc');
        $estadisticas['numero_72'] = $this->buscarXFechas(IntervencionTecnica::query(), null, 3, $delegacion_id, 'rc');
        $datos[] = $estadisticas;

        $estadisticas['nombre'] = 'Necro';
        $estadisticas['ruta'] = 'necro';
        $estadisticas['numero_24'] = $this->buscarXFechas(Necro::query(), 1, null, $delegacion_id);
        $estadisticas['numero_48'] = $this->buscarXFechas(Necro::query(), 3, 1, $delegacion_id);
        $estadisticas['numero_72'] = $this->buscarXFechas(Necro::query(), null, 3, $delegacion_id);
        $datos[] = $estadisticas;

        $estadisticas['nombre'] = 'SV';
        $estadisticas['ruta'] = 'intervenciontecnica';
        $estadisticas['numero_24'] = $this->buscarXFechas(IntervencionTecnica::query(), 1, null, $delegacion_id, 'sv');
        $estadisticas['numero_48'] = $this->buscarXFechas(IntervencionTecnica::query(), 3, 1, $delegacion_id, 'sv');
        $estadisticas['numero_72'] = $this->buscarXFechas(IntervencionTecnica::query(), null, 3, $delegacion_id, 'sv');
        $datos[] = $estadisticas;

        return response(['datos' => $datos], 200);
    }

    public function calcularEstadisticas($anio = null, $delegacion_id) {
        $year = $anio ? $anio : Carbon::now()->year;
        $fecha = \Carbon\Carbon::parse($year."-01-01");
        $inicio = $fecha->startOfYear()->format('Y-m-d H:i:s');
        $fin = $fecha->endOfYear()->format('Y-m-d H:i:s');

        if (!$this->validateDependenciaUsuario()) {
            $user = Auth::user();
            $delegacion_id = $user->dependencia_id;
        }

        $datos['estadisticas_informes_anio'] = $this->calcularEstadisticasAnio($inicio, $fin, $delegacion_id);
        $datos['estadisticas_informes_pendientes'] = $this->calcularEstadisticasInformesPorEstados($inicio, $fin, $delegacion_id, 1);
        $datos['estadisticas_informes_por_delito'] = $this->calcularEstadisticasInformesPorDelitos($inicio, $fin, $delegacion_id);
        $datos['estadisticas_informes_por_perito'] = $this->calcularEstadisticasCantidadInformesPorPerito($inicio, $fin, $delegacion_id);
        //$datos['estadisticas_informes_por_perito_por_mes'] = $this->calcularEstadisticasCantidadInformesPorPeritoPorMes($inicio, $fin);

        return response(['datos' => $datos], 200);
    }


    public function calcularEstadisticasAnio($inicio, $fin, $lugar) {
        if ($lugar != 'null') {
            $result = DB::select(
                "SELECT
       (SELECT COUNT(*) FROM intervenciones_tecnicas where fecha_hora >= '$inicio' and fecha_hora <= '$fin' and delegacion_dependencia_id = $lugar) AS cantidad_ITCC,
       (SELECT COUNT(*) FROM intervenciones_tecnicas where tipo_intervencion_tecnica_id = 2 and fecha_hora >= '$inicio' and fecha_hora <= '$fin' and delegacion_dependencia_id = $lugar) AS cantidad_SV,
       (SELECT COUNT(*) FROM intervenciones_tecnicas where tipo_intervencion_tecnica_id != 2 and fecha_hora >= '$inicio' and fecha_hora <= '$fin' and delegacion_dependencia_id = $lugar) AS cantidad_OH,
       (SELECT COUNT(*) FROM necros where fecha_hora >= '$inicio' and fecha_hora <= '$fin' and delegacion_dependencia_id = $lugar) AS cantidad_Necro,
       (SELECT COUNT(*) FROM traslados where fecha_hora >= '$inicio' and fecha_hora <= '$fin' and delegacion_dependencia_id = $lugar) AS cantidad_Traslado,
       (SELECT COUNT(*) FROM sin_efectos where fecha_hora_registro >= '$inicio' and fecha_hora_registro <= '$fin' and delegacion_dependencia_id = $lugar) AS cantidad_Sin_Efecto");
        } else {
            $result = DB::select(
                "SELECT
       (SELECT COUNT(*) FROM intervenciones_tecnicas where fecha_hora >= '$inicio' and fecha_hora <= '$fin') AS cantidad_ITCC,
       (SELECT COUNT(*) FROM intervenciones_tecnicas where tipo_intervencion_tecnica_id = 2 and fecha_hora >= '$inicio' and fecha_hora <= '$fin') AS cantidad_SV,
       (SELECT COUNT(*) FROM intervenciones_tecnicas where tipo_intervencion_tecnica_id != 2 and fecha_hora >= '$inicio' and fecha_hora <= '$fin') AS cantidad_OH,
       (SELECT COUNT(*) FROM necros where fecha_hora >= '$inicio' and fecha_hora <= '$fin') AS cantidad_Necro,
       (SELECT COUNT(*) FROM traslados where fecha_hora >= '$inicio' and fecha_hora <= '$fin') AS cantidad_Traslado,
       (SELECT COUNT(*) FROM sin_efectos where fecha_hora_registro >= '$inicio' and fecha_hora_registro <= '$fin') AS cantidad_Sin_Efecto");
        }

        return $result;
    }

    public function calcularEstadisticasInformesPorEstados($inicio, $fin, $lugar, $estado) {
        if($lugar != 'null'){
            $result = DB::select(
                "SELECT
       (SELECT COUNT(*) FROM intervenciones_tecnicas where tipo_intervencion_tecnica_id = 2 and estado_id = $estado and fecha_hora >= '$inicio' and fecha_hora <= '$fin' and delegacion_dependencia_id = $lugar) AS cantidad_SV,
    (SELECT COUNT(*) FROM intervenciones_tecnicas where tipo_intervencion_tecnica_id != 2 and estado_id = $estado and fecha_hora >= '$inicio' and fecha_hora <= '$fin' and delegacion_dependencia_id = $lugar) AS cantidad_OH,
    (SELECT COUNT(*) FROM necros where estado_id = $estado and fecha_hora >= '$inicio' and fecha_hora <= '$fin' and delegacion_dependencia_id = $lugar) AS cantidad_Necro,
    (SELECT fecha_hora FROM intervenciones_tecnicas where tipo_intervencion_tecnica_id = 2 and estado_id = $estado and fecha_hora >= '$inicio' and fecha_hora <= '$fin' and delegacion_dependencia_id = $lugar ORDER BY fecha_hora LIMIT 1) AS fecha_SV_viejo,
    (SELECT fecha_hora FROM intervenciones_tecnicas where tipo_intervencion_tecnica_id != 2 and estado_id = $estado and fecha_hora >= '$inicio' and fecha_hora <= '$fin' and delegacion_dependencia_id = $lugar ORDER BY fecha_hora LIMIT 1) AS fecha_OH_viejo,
    (SELECT fecha_hora FROM necros where estado_id = $estado and fecha_hora >= '$inicio' and fecha_hora <= '$fin' and delegacion_dependencia_id = $lugar ORDER BY fecha_hora LIMIT 1) AS fecha_Necro_viejo");
        } else {
            $result = DB::select(
                "SELECT
       (SELECT COUNT(*) FROM intervenciones_tecnicas where tipo_intervencion_tecnica_id = 2 and estado_id = $estado and fecha_hora >= '$inicio' and fecha_hora <= '$fin') AS cantidad_SV,
    (SELECT COUNT(*) FROM intervenciones_tecnicas where tipo_intervencion_tecnica_id != 2 and estado_id = $estado and fecha_hora >= '$inicio' and fecha_hora <= '$fin') AS cantidad_OH,
    (SELECT COUNT(*) FROM necros where estado_id = $estado and fecha_hora >= '$inicio' and fecha_hora <= '$fin') AS cantidad_Necro,
    (SELECT fecha_hora FROM intervenciones_tecnicas where tipo_intervencion_tecnica_id = 2 and estado_id = $estado and fecha_hora >= '$inicio' and fecha_hora <= '$fin' ORDER BY fecha_hora LIMIT 1) AS fecha_SV_viejo,
    (SELECT fecha_hora FROM intervenciones_tecnicas where tipo_intervencion_tecnica_id != 2 and estado_id = $estado and fecha_hora >= '$inicio' and fecha_hora <= '$fin' ORDER BY fecha_hora LIMIT 1) AS fecha_OH_viejo,
    (SELECT fecha_hora FROM necros where estado_id = $estado and fecha_hora >= '$inicio' and fecha_hora <= '$fin' ORDER BY fecha_hora LIMIT 1) AS fecha_Necro_viejo");
        }
        return $result;
    }

    public function calcularEstadisticasInformesPorDelitos($inicio, $fin, $lugar) {
        if ($lugar != 'null') {
            $result = DB::table('intervenciones_tecnicas')
                ->join('delitos', 'intervenciones_tecnicas.delito_id', '=', 'delitos.id')
                ->select(DB::raw('delitos.nombre, count(delitos.nombre)'))
                ->where( 'fecha_hora', '>=' ,$inicio)
                ->where('fecha_hora', '<=', $fin)
                ->where('delegacion_dependencia_id','=', $lugar)
                ->groupBy('delitos.nombre')
                ->get();
        } else {
            $result = DB::table('intervenciones_tecnicas')
                ->join('delitos', 'intervenciones_tecnicas.delito_id', '=', 'delitos.id')
                ->select(DB::raw('delitos.nombre, count(delitos.nombre)'))
                ->where( 'fecha_hora', '>=' ,$inicio)
                ->where('fecha_hora', '<=', $fin)
                ->groupBy('delitos.nombre')
                ->get();
        }

        return $result;
    }

    public function calcularEstadisticasCantidadInformesPorPerito($inicio, $fin, $lugar) {
        if($lugar != 'null'){
            $result = DB::table('intervenciones_tecnicas')
                ->leftJoin('users', 'intervenciones_tecnicas.perito_usuario_id', '=', 'users.id')
                ->select(DB::raw('users.nombre_completo as nombre_perito, count(case when intervenciones_tecnicas.estado_id = 1 or intervenciones_tecnicas.estado_id = 3 then 1 else null end) as pendientes,
             count(case when intervenciones_tecnicas.estado_id >= 4 then 1 else null end) as realizados'))
                ->where( 'fecha_hora', '>=' ,$inicio)
                ->where('fecha_hora', '<=', $fin)
                ->where('delegacion_dependencia_id','=', $lugar)
                ->groupBy('users.nombre_completo')
                ->get();
        } else {
            $result = DB::table('intervenciones_tecnicas')
                 ->leftJoin('users', 'intervenciones_tecnicas.perito_usuario_id', '=', 'users.id')
                 ->select(DB::raw('users.nombre_completo as nombre_perito, count(case when intervenciones_tecnicas.estado_id = 1 or intervenciones_tecnicas.estado_id = 3 then 1 else null end) as pendientes,
             count(case when intervenciones_tecnicas.estado_id >= 4 then 1 else null end) as realizados'))
                 ->where( 'fecha_hora', '>=' ,$inicio)
                 ->where('fecha_hora', '<=', $fin)
                 ->groupBy('users.nombre_completo')
                 ->get();
        }

        return $result;
    }

    public function calcularEstadisticasCantidadInformesPorPeritoPorMes($inicio, $fin){
        $result = DB::table('intervenciones_tecnicas')
            ->leftJoin('users', 'intervenciones_tecnicas.perito_usuario_id', '=', 'users.id')
            ->select(DB::raw("users.nombre_completo as nombre_perito, date_trunc('month', intervenciones_tecnicas.fecha_hora) AS mes, count(case when intervenciones_tecnicas.estado_id >= 4 then 1 else null end) as realizados"))
            ->where( 'fecha_hora', '>=' ,$inicio)
            ->where('fecha_hora', '<=', $fin)
            ->groupBy('users.nombre_completo')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $result->map(function ($item) {
            $mes =  explode('-', $item->mes);
            $item->mes = $mes[1];
        });

        return $result;
    }

    public function setearOpcionesFiltrosITCC()
    {
        $filtros[] = null;
        $filtros['tipo_colisiones'] = TipoColision::all(); //TIPO COLISION
        $filtros['delitos'] = Delito::orderBy('nombre', 'ASC')->get(); // DELITOS
        $tiposITCC = TipoIntervencionTecnica::all(); //PARA TRAER LOS TIPOS DE ITCC Y SUS DELITOS
        $tiposITCC->map(function ($item) {
            $item['delitos'] = $item->delitos;
        });
        $filtros['tiposITCC'] = $tiposITCC;
        $filtros['tipos_indicios'] = TipoIndicio::all();
        $filtros['tipos_informes_laboratorio'] = TipoInformesLaboratorio::all();

        return response(['filtros' => $filtros], 200);
    }

    public function setearOpcionesFiltros($delegacion_id)
    {
        $filtros[] = null;
        $filtros['perito_usuarios'] = User::where('perito', true)->where('dependencia_id', $delegacion_id)->get();  //USUARIOS
        $response = Request::get("10.100.1.150/ws/dptosmza.php")
                ->addHeaders(array('Accept' => 'application/json', 'Content-Type' => 'application/json'))
                ->send(); //DEPARTAMENTOS
        $filtros['departamentos'] = $response->body;
        $filtros['delegaciones'] = Dependencia::where('cientifica',true)->orderBy('nombre', 'ASC')
                ->get(); //DELEGACIONES
        $filtros['intervinientes'] = Dependencia::where('cientifica',false)->orderBy('nombre', 'ASC')
                ->get(); //INTERVINIENTES
        $filtros['estados'] = Estado::all(); // ESTADOS
        $filtros['estados_oficios'] = EstadoOficio::all();

        return response(['filtros' => $filtros], 200);
    }

    private function buscarXFechas($query, $desde = null, $hasta = null, $lugar, $tipo = null){
        if($lugar != 'null'){
            $query->where('delegacion_dependencia_id', '=', $lugar);
        }
        $hasta ? $hasta = Carbon::now()->subDays($hasta) : $hasta = Carbon::now();
        if($desde){
            $desde = Carbon::now()->subDays($desde);
            $query->where('estado_id', 1)
                ->whereBetween('fecha_hora', [$desde,$hasta]);
        } else {
            $query->where('estado_id', 1)
                ->where('fecha_hora', '<', $hasta);
        }
        if($tipo === 'rc'){
            $query->where('tipo_intervencion_tecnica_id', 1);
        } else if($tipo === 'sv'){
            $query->where('tipo_intervencion_tecnica_id', 2);
        }
        $resultado= $query->count();

        return $resultado;
    }

}
