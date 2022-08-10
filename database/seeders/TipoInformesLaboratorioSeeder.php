<?php

namespace Database\Seeders;

use App\Models\TipoInformesLaboratorio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class TipoInformesLaboratorioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipoInforme = new TipoInformesLaboratorio();
        $tipoInforme->nombre = 'QL (química)';
        $tipoInforme->save();

        $tipoInforme = new TipoInformesLaboratorio();
        $tipoInforme->nombre = 'B (balística)';
        $tipoInforme->save();

        $tipoInforme = new TipoInformesLaboratorio();
        $tipoInforme->nombre = 'IB (identificación balística)';
        $tipoInforme->save();

        $tipoInforme = new TipoInformesLaboratorio();
        $tipoInforme->nombre = 'R (rastro)';
        $tipoInforme->save();

        $tipoInforme = new TipoInformesLaboratorio();
        $tipoInforme->nombre = 'IC (informe cotejo de rastro)';
        $tipoInforme->save();

        $tipoInforme = new TipoInformesLaboratorio();
        $tipoInforme->nombre = 'A (informe de valoración)';
        $tipoInforme->save();

        $tipoInforme = new TipoInformesLaboratorio();
        $tipoInforme->nombre = 'D (documentologico)';
        $tipoInforme->save();

        $tipoInforme = new TipoInformesLaboratorio();
        $tipoInforme->nombre = 'ID (identificación docu)';
        $tipoInforme->save();
    }
}
