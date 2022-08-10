<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Traslado extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_hora',
        'expediente_id',
        'perito_usuario_id',
        'acompanante_usuario_id',
        'cadaver_nombre',
        'cadaver_documento_id',
        'observaciones',
        'legajo_cuerpo_medico_forense',
        'lugar_id',
        'foto',
        'identificado',
        'delegacion_dependencia_id',
        'interviniento_dependencia_id',
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
        'perito_usuario_id' => 'integer',
        'acompanante_usuario_id' => 'integer',
        'cadaver_documento_id' => 'integer',
        'lugar_id' => 'integer',
        'foto' => 'boolean',
        'delegacion_dependencia_id' => 'integer',
        'interviniento_dependencia_id' => 'integer'
    ];


    public function expediente()
    {
        return $this->belongsTo(Expediente::class);
    }

    public function peritoUsuario()
    {
        return $this->belongsTo(User::class);
    }

    public function acompananteUsuario()
    {
        return $this->belongsTo(User::class);
    }

    public function cadaverDocumento()
    {
        return $this->belongsTo(Documento::class);
    }

    public function lugar()
    {
        return $this->belongsTo(Lugar::class);
    }

    public function delegacionDependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function intervinientoDependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function oficios()
    {
        return $this->morphMany(Oficio::class, 'oficiable');
    }

    public function getIsEditableAttribute()
    {
       return $this->created_at >= Carbon::now()->subDay();
    }
}
