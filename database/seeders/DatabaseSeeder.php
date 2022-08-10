<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                RolePermisisionSeeder::class,
                ConfigInicialSeeder::class,
                UserSeeder::class,
                RolesViejosSeeder::class,
                PermisosConsultarSeeder::class,
                PermisosEditarEstadoServicios::class,
                TipoIndicioSeeder::class,
                TipoIntervencionTecnicaSeeder::class,
                DelitoSeeder::class,
                EstadoOficioSeeder::class,
                PermisosVerEstadisticas::class,
                TipoInformesLaboratorioSeeder::class,
                RoleJefeCientificaPermisos::class,
                AsignarPermisoCrearSinEfectoViejo::class,
                RolesPermissionsTen::class
            ]
        );
    }
}
