<?php

namespace Database\Seeders;

use App\Constant\PermissionConstant;
use App\Constant\RolConstant;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AsignarPermisoCrearSinEfectoViejo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => PermissionConstant::CREAR_SIN_EFECTO_VIEJO]);
        $role = Role::findByName(RolConstant::ADMIN_SISTEMAS);
        $role->givePermissionTo([
            PermissionConstant::CREAR_SIN_EFECTO_VIEJO
        ]);

        $role = Role::findByName(RolConstant::JEFE_CIENTIFICA);
        $role->givePermissionTo([
            PermissionConstant::CREAR_SIN_EFECTO_VIEJO
        ]);

    }
}
