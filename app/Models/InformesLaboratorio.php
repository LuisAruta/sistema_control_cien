<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class InformesLaboratorio extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'informes_laboratorios';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero_informe',
        'tipo_informes_laboratorio_id',
        'intervencion_tecnica_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'intervencion_tecnica_id' => 'integer',
        'tipo_informes_laboratorio_id' => 'integer'
    ];

    public function intervencionTecnica()
    {
        return $this->belongsTo(IntervencionTecnica::class);
    }

    public function tipoInformesLaboratorio()
    {
        return $this->belongsTo(TipoInformesLaboratorio::class);
    }
}
