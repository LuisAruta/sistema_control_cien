<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Detenido extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'documento_id',
        'fecha_nacimiento'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'documento_id' => 'integer',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function documento()
    {
        return $this->belongsTo(Documento::class)->select('id','tipo_documento','numero');
    }

    public function IntervencionesTecnicas()
    {
        return $this->belongsToMany(IntervencionTecnica::class, 'detenido_intervencion_tecnica')->withTimestamps();
    }
}
