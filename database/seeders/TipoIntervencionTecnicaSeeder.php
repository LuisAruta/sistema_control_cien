<?php

namespace Database\Seeders;

use App\Models\TipoIntervencionTecnica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class TipoIntervencionTecnicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipoIntervencion = new TipoIntervencionTecnica();
        $tipoIntervencion->nombre = 'Reconstrucción Criminal';
        $tipoIntervencion->sigla = 'RC';
        $tipoIntervencion->save();

        $tipoIntervencion = new TipoIntervencionTecnica();
        $tipoIntervencion->nombre = 'Siniestro Vial';
        $tipoIntervencion->sigla = 'SV';
        $tipoIntervencion->save();
    }
}
