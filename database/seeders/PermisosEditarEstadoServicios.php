<?php

namespace Database\Seeders;

use App\Constant\PermissionConstant;
use App\Constant\RolConstant;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermisosEditarEstadoServicios extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::create(['name' => PermissionConstant::EDITAR_ESTADO_SERVICIOS]);

        $role = Role::findByName(RolConstant::ADMIN_SISTEMAS);
        $role->givePermissionTo([
            PermissionConstant::EDITAR_ESTADO_SERVICIOS
        ]);

        $role = Role::findByName(RolConstant::CONTROL_DE_INFORMES);
        $role->givePermissionTo([
            PermissionConstant::EDITAR_ESTADO_SERVICIOS
        ]);
    }
}
