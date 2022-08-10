<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoIntervencionTecnica extends Model
{
    use SoftDeletes;

    protected $table = 'tipo_intervencion_tecnica';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'sigla'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function delitos()
    {
        return $this->belongsToMany(Delito::class, 'delito_tipo_intervencion_tecnica')->withTimestamps();
    }
}
