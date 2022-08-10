<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstadoOficioHistorico extends Model
{
    use SoftDeletes;
    protected $table = 'estado_oficio_historico';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'oficio_id' => 'integer',
        'estado_oficio_id' => 'integer',
        'users_id' => 'integer',
    ];

    public function oficio()
    {
        return $this->belongsTo(Oficio::class);
    }

    public function estadoOficio()
    {
        return $this->belongsTo(EstadoOficio::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

}
