<?php

namespace Database\Seeders;

use App\Models\EstadoOficio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class EstadoOficioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estado = new EstadoOficio();
        $estado->nombre = 'Pendiente';
        $estado->save();

        $estado = new EstadoOficio();
        $estado->nombre = 'Resuelto';
        $estado->save();
    }
}
