<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Indicio extends Model
{
    use SoftDeletes;

    protected $table = 'indicios';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cantidad',
        'detalle',
        'intervencion_tecnica_id',
        'tipo_indicio_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'intervencion_tecnica_id' => 'integer',
        'tipo_indicio_id' => 'integer'
    ];

    public function intervencionTecnica()
    {
        return $this->belongsTo(IntervencionTecnica::class);
    }

    public function tipoIndicio()
    {
        return $this->belongsTo(TipoIndicio::class);
    }
}
