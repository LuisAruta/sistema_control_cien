<?php

namespace Database\Seeders;

use App\Models\Delito;
use App\Models\TipoIntervencionTecnica;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DelitoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //Delitos
        $estado = new Delito();
        $estado->nombre = 'Aborto';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);

        $estado = new Delito();
        $estado->nombre = 'Abuso de Arma';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);

        $estado = new Delito();
        $estado->nombre = 'Abuso Sexual';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);

        $estado = new Delito();
        $estado->nombre = 'Accidente';
        $estado->save();
        $estado->tiposIntervencion()->attach([1,2]);

        $estado = new Delito();
        $estado->nombre = 'Accidente Seguido de muerte';
        $estado->save();
        $estado->tiposIntervencion()->attach([1,2]);

        $estado = new Delito();
        $estado->nombre = 'Amenazas agravadas';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);

        $estado = new Delito();
        $estado->nombre = 'Colaboración/asistencia';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);

        $estado = new Delito();
        $estado->nombre = 'Daño';
        $estado->save();
        $estado->tiposIntervencion()->attach([1,2]);

        $estado = new Delito();
        $estado->nombre = 'Daño Agravado';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);

        $estado = new Delito();
        $estado->nombre = 'Delito';
        $estado->save();
        $estado->tiposIntervencion()->attach([1,2]);

        $estado = new Delito();
        $estado->nombre = 'Hallazgo';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);

        $estado = new Delito();
        $estado->nombre = 'Hecho';
        $estado->save();
        $estado->tiposIntervencion()->attach([1,2]);

        $estado = new Delito();
        $estado->nombre = 'Homicidio';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);

        $estado = new Delito();
        $estado->nombre = 'Homicidio Culposo';
        $estado->save();
        $estado->tiposIntervencion()->attach([1,2]);

        $estado = new Delito();
        $estado->nombre = 'Hurto';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);

        $estado = new Delito();
        $estado->nombre = 'Hurto Agravado';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);

        $estado = new Delito();
        $estado->nombre = 'Instigación y/o Ayuda al Suicidio';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);

        $estado = new Delito();
        $estado->nombre = 'Instigación y/o Ayuda al Suicidio en Tentativa';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);

        $estado = new Delito();
        $estado->nombre = 'Lesiones';
        $estado->save();
        $estado->tiposIntervencion()->attach([1,2]);

        $estado = new Delito();
        $estado->nombre = 'Lesiones Culposas';
        $estado->save();
        $estado->tiposIntervencion()->attach([1,2]);

        $estado = new Delito();
        $estado->nombre = 'Ley 9024';
        $estado->save();
        $estado->tiposIntervencion()->attach([1,2]);

        $estado = new Delito();
        $estado->nombre = 'Muerte';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);

        $estado = new Delito();
        $estado->nombre = 'No Consigna';
        $estado->save();
        $estado->tiposIntervencion()->attach([1,2]);

        $estado = new Delito();
        $estado->nombre = 'Portación';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);

        $estado = new Delito();
        $estado->nombre = 'Robo';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);

        $estado = new Delito();
        $estado->nombre = 'Robo Agravado';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);

        $estado = new Delito();
        $estado->nombre = 'Tenencia';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);

        $estado = new Delito();
        $estado->nombre = 'Vehículo Habido';
        $estado->save();
        $estado->tiposIntervencion()->attach(1);
    }
}
