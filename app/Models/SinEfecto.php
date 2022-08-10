<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class SinEfecto extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'sin_efectos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fecha_hora_registro',
        'fecha_hora_solicitud',
        'usuario_id',
        'delegacion_dependencia_id',
        'lugar_id',
        'funcionario',
        'descripcion'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'numero_de_registro' => 'integer',
        'delegacion_dependencia_id' => 'integer',
        'lugar_id' => 'integer',
        'usuario_id' => 'integer',
    ];

    public function delegacionDependencia()
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function lugar()
    {
        return $this->belongsTo(Lugar::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
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
