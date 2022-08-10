<?php

namespace Database\Seeders;

use App\Constant\PermissionConstant;
use App\Constant\RolConstant;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesViejosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => PermissionConstant::CREAR_NECRO_VIEJO]);
        Permission::create(['name' => PermissionConstant::CREAR_TRASLADO_VIEJO]);
        Permission::create(['name' => PermissionConstant::CREAR_INTERVENCION_TECNICA_VIEJO]);

        $role = Role::findByName(RolConstant::ADMIN_SISTEMAS);
        $role->givePermissionTo([
            PermissionConstant::CREAR_NECRO_VIEJO,
            PermissionConstant::CREAR_TRASLADO_VIEJO,
            PermissionConstant::CREAR_INTERVENCION_TECNICA_VIEJO
        ]);
    }
}
