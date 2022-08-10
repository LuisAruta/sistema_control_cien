<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Delito
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Delito newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Delito newQuery()
 * @method static \Illuminate\Database\Query\Builder|Delito onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Delito query()
 * @method static \Illuminate\Database\Eloquent\Builder|Delito whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delito whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delito whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delito whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Delito whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Delito withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Delito withoutTrashed()
 */
	class Delito extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Dependencia
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $grupo
 * @property bool $cientifica
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|Dependencia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Dependencia newQuery()
 * @method static \Illuminate\Database\Query\Builder|Dependencia onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Dependencia query()
 * @method static \Illuminate\Database\Eloquent\Builder|Dependencia whereCientifica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dependencia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dependencia whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dependencia whereGrupo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dependencia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dependencia whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Dependencia whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Dependencia withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Dependencia withoutTrashed()
 */
	class Dependencia extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\Documento
 *
 * @property int $id
 * @property string $tipo_documento
 * @property string $numero
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Documento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Documento newQuery()
 * @method static \Illuminate\Database\Query\Builder|Documento onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Documento query()
 * @method static \Illuminate\Database\Eloquent\Builder|Documento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documento whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documento whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documento whereTipoDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Documento whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Documento withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Documento withoutTrashed()
 */
	class Documento extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Estado
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Estado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Estado newQuery()
 * @method static \Illuminate\Database\Query\Builder|Estado onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Estado query()
 * @method static \Illuminate\Database\Eloquent\Builder|Estado whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Estado whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Estado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Estado whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Estado whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Estado withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Estado withoutTrashed()
 */
	class Estado extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Expediente
 *
 * @property int $id
 * @property string $numero_expediente
 * @property string|null $caratula
 * @property string $origen
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Expediente newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expediente newQuery()
 * @method static \Illuminate\Database\Query\Builder|Expediente onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Expediente query()
 * @method static \Illuminate\Database\Eloquent\Builder|Expediente whereCaratula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expediente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expediente whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expediente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expediente whereNumeroExpediente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expediente whereOrigen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Expediente whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Expediente withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Expediente withoutTrashed()
 */
	class Expediente extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Lugar
 *
 * @property int $id
 * @property string|null $latitud
 * @property string|null $longitud
 * @property string|null $departamento
 * @property string|null $barrio
 * @property string|null $localidad
 * @property string|null $calle
 * @property string|null $numero
 * @property string|null $numero_departamento
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Lugar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lugar newQuery()
 * @method static \Illuminate\Database\Query\Builder|Lugar onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Lugar query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lugar whereBarrio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lugar whereCalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lugar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lugar whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lugar whereDepartamento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lugar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lugar whereLatitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lugar whereLocalidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lugar whereLongitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lugar whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lugar whereNumeroDepartamento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lugar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Lugar withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Lugar withoutTrashed()
 */
	class Lugar extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Necro
 *
 * @property int $id
 * @property string|null $fecha_hora
 * @property int|null $expediente_id
 * @property int|null $perito_usuario_id
 * @property string|null $cadaver_nombre
 * @property int|null $cadaver_documento_id
 * @property string|null $legajo_cuerpo_medico_forense
 * @property int|null $lugar_id
 * @property bool $foto
 * @property bool|null $identificado
 * @property int|null $delegacion_dependencia_id
 * @property int|null $interviniento_dependencia_id
 * @property int|null $estado_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $observaciones
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Documento|null $cadaverDocumento
 * @property-read \App\Models\Dependencia|null $delegacionDependencia
 * @property-read \App\Models\Estado|null $estado
 * @property-read \App\Models\Expediente|null $expediente
 * @property-read mixed $is_editable
 * @property-read \App\Models\Dependencia|null $intervinientoDependencia
 * @property-read \App\Models\Lugar|null $lugar
 * @property-read \App\Models\User|null $peritoUsuario
 * @method static \Illuminate\Database\Eloquent\Builder|Necro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Necro newQuery()
 * @method static \Illuminate\Database\Query\Builder|Necro onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Necro query()
 * @method static \Illuminate\Database\Eloquent\Builder|Necro whereCadaverDocumentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Necro whereCadaverNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Necro whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Necro whereDelegacionDependenciaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Necro whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Necro whereEstadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Necro whereExpedienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Necro whereFechaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Necro whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Necro whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Necro whereIdentificado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Necro whereIntervinientoDependenciaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Necro whereLegajoCuerpoMedicoForense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Necro whereLugarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Necro whereObservaciones($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Necro wherePeritoUsuarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Necro whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Necro withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Necro withoutTrashed()
 */
	class Necro extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\OAuthProvider
 *
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string $provider_user_id
 * @property string|null $access_token
 * @property string|null $refresh_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthProvider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthProvider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthProvider query()
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthProvider whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthProvider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthProvider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthProvider whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthProvider whereProviderUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthProvider whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthProvider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OAuthProvider whereUserId($value)
 */
	class OAuthProvider extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ReconstrucionCriminal
 *
 * @property int $id
 * @property string|null $fecha_hora
 * @property int|null $expediente_id
 * @property string|null $descripcion
 * @property bool $plano
 * @property bool $foto
 * @property bool $video
 * @property int|null $cantidad_dactilares
 * @property int|null $cantidad_palmares
 * @property int|null $cantidad_calzados
 * @property int|null $cantidad_papilares
 * @property int|null $cantidad_celulas_epiteliales
 * @property int|null $delito_id
 * @property int|null $delegacion_dependencia_id
 * @property int|null $interviniento_dependencia_id
 * @property int|null $lugar_id
 * @property int $estado_id
 * @property int|null $perito_usuario_id
 * @property int|null $tecnico_revelador_usuario_id
 * @property int|null $planimetrista_usuario_id
 * @property int|null $fotografo_usuario_id
 * @property int|null $video_usuario_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Dependencia|null $delegacionDependencia
 * @property-read \App\Models\Delito|null $delito
 * @property-read \App\Models\Estado $estado
 * @property-read \App\Models\Expediente|null $expediente
 * @property-read \App\Models\User|null $fotografoUsuario
 * @property-read mixed $is_editable
 * @property-read \App\Models\Dependencia|null $intervinientoDependencia
 * @property-read \App\Models\Lugar|null $lugar
 * @property-read \App\Models\User|null $peritoUsuario
 * @property-read \App\Models\User|null $planimetristaUsuario
 * @property-read \App\Models\User|null $tecnicoReveladorUsuario
 * @property-read \App\Models\User|null $videoUsuario
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal newQuery()
 * @method static \Illuminate\Database\Query\Builder|ReconstrucionCriminal onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereCantidadCalzados($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereCantidadCelulasEpiteliales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereCantidadDactilares($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereCantidadPalmares($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereCantidadPapilares($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereDelegacionDependenciaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereDelitoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereEstadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereExpedienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereFechaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereFotografoUsuarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereIntervinientoDependenciaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereLugarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal wherePeritoUsuarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal wherePlanimetristaUsuarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal wherePlano($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereTecnicoReveladorUsuarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReconstrucionCriminal whereVideoUsuarioId($value)
 * @method static \Illuminate\Database\Query\Builder|ReconstrucionCriminal withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ReconstrucionCriminal withoutTrashed()
 */
	class ReconstrucionCriminal extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\SiniestroVial
 *
 * @property int $id
 * @property string|null $fecha_hora
 * @property int|null $expediente_id
 * @property string|null $descripcion
 * @property int|null $perito_usuario_id
 * @property int|null $fotografo_usuario_id
 * @property int|null $planimetrista_usuario_id
 * @property int|null $delegacion_dependencia_id
 * @property int|null $interviniento_dependencia_id
 * @property int $estado_id
 * @property int|null $tipo_colision_id
 * @property int|null $delito_id
 * @property int|null $lugar_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Dependencia|null $delegacionDependencia
 * @property-read \App\Models\Delito|null $delito
 * @property-read \App\Models\Estado $estado
 * @property-read \App\Models\Expediente|null $expediente
 * @property-read \App\Models\User|null $fotografoUsuario
 * @property-read mixed $is_editable
 * @property-read \App\Models\Dependencia|null $intervinientoDependencia
 * @property-read \App\Models\Lugar|null $lugar
 * @property-read \App\Models\User|null $peritoUsuario
 * @property-read \App\Models\User|null $planimetristaUsuario
 * @property-read \App\Models\TipoColision|null $tipoColision
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial newQuery()
 * @method static \Illuminate\Database\Query\Builder|SiniestroVial onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial query()
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial whereDelegacionDependenciaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial whereDelitoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial whereEstadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial whereExpedienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial whereFechaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial whereFotografoUsuarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial whereIntervinientoDependenciaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial whereLugarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial wherePeritoUsuarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial wherePlanimetristaUsuarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial whereTipoColisionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SiniestroVial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|SiniestroVial withTrashed()
 * @method static \Illuminate\Database\Query\Builder|SiniestroVial withoutTrashed()
 */
	class SiniestroVial extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\TipoColision
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TipoColision newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoColision newQuery()
 * @method static \Illuminate\Database\Query\Builder|TipoColision onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoColision query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoColision whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoColision whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoColision whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoColision whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoColision whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|TipoColision withTrashed()
 * @method static \Illuminate\Database\Query\Builder|TipoColision withoutTrashed()
 */
	class TipoColision extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Traslado
 *
 * @property int $id
 * @property string|null $fecha_hora
 * @property int|null $expediente_id
 * @property int|null $perito_usuario_id
 * @property int|null $acompanante_usuario_id
 * @property string|null $cadaver_nombre
 * @property int|null $cadaver_documento_id
 * @property string|null $legajo_cuerpo_medico_forense
 * @property int|null $lugar_id
 * @property bool|null $foto
 * @property bool|null $identificado
 * @property int|null $delegacion_dependencia_id
 * @property int|null $interviniento_dependencia_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $acompananteUsuario
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Documento|null $cadaverDocumento
 * @property-read \App\Models\Dependencia|null $delegacionDependencia
 * @property-read \App\Models\Expediente|null $expediente
 * @property-read mixed $is_editable
 * @property-read \App\Models\Dependencia|null $intervinientoDependencia
 * @property-read \App\Models\Lugar|null $lugar
 * @property-read \App\Models\User|null $peritoUsuario
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado newQuery()
 * @method static \Illuminate\Database\Query\Builder|Traslado onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado query()
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado whereAcompananteUsuarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado whereCadaverDocumentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado whereCadaverNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado whereDelegacionDependenciaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado whereExpedienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado whereFechaHora($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado whereIdentificado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado whereIntervinientoDependenciaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado whereLegajoCuerpoMedicoForense($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado whereLugarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado wherePeritoUsuarioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traslado whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Traslado withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Traslado withoutTrashed()
 */
	class Traslado extends \Eloquent implements \OwenIt\Auditing\Contracts\Auditable {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property int $dependencia_id
 * @property int $documento_id
 * @property string $nombre_completo
 * @property bool $perito
 * @property string|null $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Dependencia $dependencia
 * @property-read \App\Models\Documento $documento
 * @property-read string $photo_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OAuthProvider[] $oauthProviders
 * @property-read int|null $oauth_providers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDependenciaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDocumentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNombreCompleto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePerito($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 */
	class User extends \Eloquent implements \Tymon\JWTAuth\Contracts\JWTSubject, \OwenIt\Auditing\Contracts\Auditable {}
}

