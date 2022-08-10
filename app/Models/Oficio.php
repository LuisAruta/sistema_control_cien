<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Oficio extends Model
{
    use SoftDeletes;

    protected $table = 'oficios';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'fecha_ingreso',
       'instancia_solicitante_id',
       'observacion',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function oficiable()
    {
        return $this->morphTo();
    }

    public function instanciaSolicitante()
    {
        return $this->belongsTo(Dependencia::class);
    }

    public function estadoOficio()
    {
        return $this->belongsTo(EstadoOficio::class);
    }

    public function historicoEstadoOficio(){
        return $this->hasMany(EstadoOficioHistorico::class);
    }

}
