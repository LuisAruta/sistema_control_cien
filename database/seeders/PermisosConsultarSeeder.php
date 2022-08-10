<?php

namespace Database\Seeders;

use App\Constant\PermissionConstant;
use App\Constant\RolConstant;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermisosConsultarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => PermissionConstant::CONSULTAR_PARA_TODAS_LAS_DEPENDENCIAS]);
        Permission::create(['name' => PermissionConstant::CREAR_PARA_TODAS_LAS_DEPENDENCIAS]);

        $role = Role::findByName(RolConstant::ADMIN_SISTEMAS);
        $role->givePermissionTo([
            PermissionConstant::CONSULTAR_PARA_TODAS_LAS_DEPENDENCIAS,
            PermissionConstant::CREAR_PARA_TODAS_LAS_DEPENDENCIAS
        ]);

    }
}
