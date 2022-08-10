<?php

namespace Database\Seeders;

use App\Models\TipoIndicio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class TipoIndicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipoMuestra = new TipoIndicio();
        $tipoMuestra->nombre = 'Rastros Dactilares';
        $tipoMuestra->save();

        $tipoMuestra = new TipoIndicio();
        $tipoMuestra->nombre = 'Rastros Palmares';
        $tipoMuestra->save();

        $tipoMuestra = new TipoIndicio();
        $tipoMuestra->nombre = 'Rastros Papilares';
        $tipoMuestra->save();

        $tipoMuestra = new TipoIndicio();
        $tipoMuestra->nombre = 'Rastros de Calzados';
        $tipoMuestra->save();

        $tipoMuestra = new TipoIndicio();
        $tipoMuestra->nombre = 'Muestras Epiteliales';
        $tipoMuestra->save();

        $tipoMuestra = new TipoIndicio();
        $tipoMuestra->nombre = 'Muestras Hemáticas';
        $tipoMuestra->save();

        $tipoMuestra = new TipoIndicio();
        $tipoMuestra->nombre = 'Pelos';
        $tipoMuestra->save();

        $tipoMuestra = new TipoIndicio();
        $tipoMuestra->nombre = 'Armas';
        $tipoMuestra->save();

        $tipoMuestra = new TipoIndicio();
        $tipoMuestra->nombre = 'Vainas';
        $tipoMuestra->save();

        $tipoMuestra = new TipoIndicio();
        $tipoMuestra->nombre = 'Cartuchos';
        $tipoMuestra->save();

        $tipoMuestra = new TipoIndicio();
        $tipoMuestra->nombre = 'Proyectiles';
        $tipoMuestra->save();

        $tipoMuestra = new TipoIndicio();
        $tipoMuestra->nombre = 'Posibles restos balísticos';
        $tipoMuestra->save();

        $tipoMuestra = new TipoIndicio();
        $tipoMuestra->nombre = 'Pintura';
        $tipoMuestra->save();

        $tipoMuestra = new TipoIndicio();
        $tipoMuestra->nombre = 'Restos Plásticos';
        $tipoMuestra->save();

        $tipoMuestra = new TipoIndicio();
        $tipoMuestra->nombre = 'Otro (detallar)';
        $tipoMuestra->save();
    }
}
