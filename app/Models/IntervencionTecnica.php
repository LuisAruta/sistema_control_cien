<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class IntervencionTecnica extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'intervenciones_tecnicas';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_hora',
        'expediente_id',
        'descripcion',
        'plano',
        'foto',
        'video',
        'perito_usuario_id',
        'tecnico_revelador_usuario_id',
        'planimetrista_usuario_id',
        'fotografo_usuario_id',
        'video_usuario_id',
        'secretario_usuario_id',
        'delegacion_dependencia_id',
        'interviniento_dependencia_id',
        'tipo_colision_id',
        'delito_id',
        'lugar_id',
        'tipo_intervencion_tecnica',
        'estado_id',
        'oficios'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'numero_de_registro' => 'integer',
        'expediente_id' => 'integer',
        'plano' => 'boolean',
        'foto' => 'boolean',
        'video' => 'boolean',
        'delito_id' => 'integer',
        'delegacion_dependencia_id' => 'integer',
        'interviniento_dependencia_id' => 'integer',
        'lugar_id' => 'integer',
        'estado_id' => 'integer',
        'perito_usuario_id' => 'integer',
        'secretario_usuario_id' => 'integer',
        'tecnico_revelador_usuario_id' => 'integer',
        'planimetrista_usuario_id' => 'integer',
        'fotografo_usuario_id' => 'integer',
        'video_usuario_id' => 'integer',
        'tipo_colision_id' => 'integer',
        'tipo_intervencion_tecnica_id' => 'integer'
    ];


    public function expediente()
    {
        return $this->belongsTo(Expediente::class);
    }

    public function delito()
    {
        return $this->belongsTo(Delito::class);
    }

    public function delegacionDependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function intervinientoDependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function lugar()
    {
        return $this->belongsTo(Lugar::class);
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class)->select('id', 'nombre');
    }

    public function peritoUsuario()
    {
        return $this->belongsTo(User::class);
    }

    public function tecnicoReveladorUsuario()
    {
        return $this->belongsTo(User::class);
    }

    public function secretarioUsuario()
    {
        return $this->belongsTo(User::class);
    }

    public function planimetristaUsuario()
    {
        return $this->belongsTo(User::class);
    }

    public function fotografoUsuario()
    {
        return $this->belongsTo(User::class);
    }

    public function videoUsuario()
    {
        return $this->belongsTo(User::class);
    }

    public function tipoColision()
    {
        return $this->belongsTo(TipoColision::class)->select('id', 'nombre');
    }

    public function tipoIntervencionTecnica()
    {
        return $this->belongsTo(TipoIntervencionTecnica::class)->select('id', 'nombre', 'sigla');
    }

    public function indicios()
    {
        return $this->hasMany(Indicio::class);
    }

    public function informesLaboratorio()
    {
        return $this->hasMany(InformesLaboratorio::class);
    }

    public function oficios()
    {
        return $this->morphMany(Oficio::class, 'oficiable');
    }

    public function detenidos()
    {
        return $this->belongsToMany(Detenido::class, 'detenido_intervencion_tecnica')->withTimestamps();
    }

    public function victimas()
    {
        return $this->belongsToMany(Victima::class, 'victima_intervencion_tecnica')->withTimestamps();
    }

    public function getIsEditableAttribute()
    {
        return $this->created_at >= Carbon::now()->subDay();
    }
}
