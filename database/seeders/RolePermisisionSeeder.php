<?php

namespace Database\Seeders;

use App\Constant\PermissionConstant;
use App\Constant\RolConstant;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermisisionSeeder extends Seeder
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


        Permission::create(['name' => PermissionConstant::CREAR_NECRO]);
        Permission::create(['name' => PermissionConstant::EDITAR_NECRO]);
        Permission::create(['name' => PermissionConstant::CONSULTAR_NECRO]);
        Permission::create(['name' => PermissionConstant::ELIMINAR_NECRO]);

        Permission::create(['name' => PermissionConstant::CREAR_TRASLADO]);
        Permission::create(['name' => PermissionConstant::EDITAR_TRASLADO]);
        Permission::create(['name' => PermissionConstant::CONSULTAR_TRASLADO]);
        Permission::create(['name' => PermissionConstant::ELIMINAR_TRASLADO]);

        Permission::create(['name' => PermissionConstant::CREAR_INTERVENCION_TECNICA]);
        Permission::create(['name' => PermissionConstant::EDITAR_INTERVENCION_TECNICA]);
        Permission::create(['name' => PermissionConstant::CONSULTAR_INTERVENCION_TECNICA]);
        Permission::create(['name' => PermissionConstant::ELIMINAR_INTERVENCION_TECNICA]);

        Permission::create(['name' => PermissionConstant::CREAR_OFICIO]);
        Permission::create(['name' => PermissionConstant::EDITAR_OFICIO]);
        Permission::create(['name' => PermissionConstant::CONSULTAR_OFICIO]);
        Permission::create(['name' => PermissionConstant::ELIMINAR_OFICIO]);

        Permission::create(['name' => PermissionConstant::CREAR_SIN_EFECTO]);
        Permission::create(['name' => PermissionConstant::EDITAR_SIN_EFECTO]);
        Permission::create(['name' => PermissionConstant::CONSULTAR_SIN_EFECTO]);
        Permission::create(['name' => PermissionConstant::ELIMINAR_SIN_EFECTO]);

        Permission::create(['name' => PermissionConstant::CREAR_USUARIO]);
        Permission::create(['name' => PermissionConstant::EDITAR_USUARIO]);
        Permission::create(['name' => PermissionConstant::CONSULTAR_USUARIO]);
        Permission::create(['name' => PermissionConstant::ELIMINAR_USUARIO]);

        Permission::create(['name' => PermissionConstant::CREAR_DEPENDENCIA]);
        Permission::create(['name' => PermissionConstant::EDITAR_DEPENDENCIA]);
        Permission::create(['name' => PermissionConstant::CONSULTAR_DEPENDENCIA]);
        Permission::create(['name' => PermissionConstant::ELIMINAR_DEPENDENCIA]);

        Permission::create(['name' => PermissionConstant::CREAR_DELITO]);
        Permission::create(['name' => PermissionConstant::EDITAR_DELITO]);
        Permission::create(['name' => PermissionConstant::CONSULTAR_DELITO]);
        Permission::create(['name' => PermissionConstant::ELIMINAR_DELITO]);

        Permission::create(['name' => PermissionConstant::CREAR_TIPO_COLISION]);
        Permission::create(['name' => PermissionConstant::EDITAR_TIPO_COLISION]);
        Permission::create(['name' => PermissionConstant::CONSULTAR_TIPO_COLISION]);
        Permission::create(['name' => PermissionConstant::ELIMINAR_TIPO_COLISION]);

        Permission::create(['name' => PermissionConstant::CREAR_ESTADO_INFORME_TECNICO]);
        Permission::create(['name' => PermissionConstant::EDITAR_ESTADO_INFORME_TECNICO]);
        Permission::create(['name' => PermissionConstant::CONSULTAR_ESTADO_INFORME_TECNICO]);
        Permission::create(['name' => PermissionConstant::ELIMINAR_ESTADO_INFORME_TECNICO]);

        Permission::create(['name' => PermissionConstant::CONSULTAR_EXPEDIENTE]);
        Permission::create(['name' => PermissionConstant::CONSULTAR_PERSONAL]);
        Permission::create(['name' => PermissionConstant::CONSULTAR_RENAPER]);

        Permission::create(['name' => PermissionConstant::EDITAR_NECRO_VIEJO]);
        Permission::create(['name' => PermissionConstant::EDITAR_TRASLADO_VIEJO]);
        Permission::create(['name' => PermissionConstant::EDITAR_INTERVENCION_TECNICA_VIEJO]);
        Permission::create(['name' => PermissionConstant::EDITAR_SIN_EFECTO_VIEJO]);

        $role = Role::create(['name' => RolConstant::ADMIN_SISTEMAS]);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => RolConstant::OPERADOR]);
        $role->givePermissionTo([
            PermissionConstant::CREAR_NECRO,
            PermissionConstant::CONSULTAR_NECRO,
            PermissionConstant::CREAR_TRASLADO,
            PermissionConstant::CONSULTAR_TRASLADO,
            PermissionConstant::CREAR_INTERVENCION_TECNICA,
            PermissionConstant::CONSULTAR_INTERVENCION_TECNICA,
            PermissionConstant::CREAR_SIN_EFECTO,
            PermissionConstant::CONSULTAR_SIN_EFECTO,
            PermissionConstant::CONSULTAR_OFICIO,
            PermissionConstant::CONSULTAR_DELITO,
            PermissionConstant::CONSULTAR_TIPO_COLISION,
            PermissionConstant::CONSULTAR_DEPENDENCIA,
            PermissionConstant::CONSULTAR_ESTADO_INFORME_TECNICO,
            PermissionConstant::CONSULTAR_EXPEDIENTE,
            PermissionConstant::CONSULTAR_PERSONAL,
            PermissionConstant::CONSULTAR_RENAPER
        ]);


        $role = Role::create(['name' => RolConstant::OFICIAL_DE_SERVICIO]);
        $role->givePermissionTo([
            PermissionConstant::CREAR_NECRO,
            PermissionConstant::EDITAR_NECRO,
            PermissionConstant::CONSULTAR_NECRO,
            PermissionConstant::CREAR_TRASLADO,
            PermissionConstant::EDITAR_TRASLADO,
            PermissionConstant::CONSULTAR_TRASLADO,
            PermissionConstant::CREAR_INTERVENCION_TECNICA,
            PermissionConstant::EDITAR_INTERVENCION_TECNICA,
            PermissionConstant::CONSULTAR_INTERVENCION_TECNICA,
            PermissionConstant::CREAR_OFICIO,
            PermissionConstant::EDITAR_OFICIO,
            PermissionConstant::CONSULTAR_OFICIO,
            PermissionConstant::CREAR_SIN_EFECTO,
            PermissionConstant::EDITAR_SIN_EFECTO,
            PermissionConstant::CONSULTAR_SIN_EFECTO,
            PermissionConstant::CONSULTAR_DELITO,
            PermissionConstant::CONSULTAR_TIPO_COLISION,
            PermissionConstant::CONSULTAR_DEPENDENCIA,
            PermissionConstant::CONSULTAR_ESTADO_INFORME_TECNICO,
            PermissionConstant::CONSULTAR_EXPEDIENTE,
            PermissionConstant::CONSULTAR_PERSONAL,
            PermissionConstant::CONSULTAR_RENAPER
        ]);

        $role = Role::create(['name' => RolConstant::CONTROL_DE_INFORMES]);
        $role->givePermissionTo([
            PermissionConstant::CREAR_NECRO,
            PermissionConstant::EDITAR_NECRO,
            PermissionConstant::CONSULTAR_NECRO,
            PermissionConstant::ELIMINAR_NECRO,
            PermissionConstant::CREAR_TRASLADO,
            PermissionConstant::EDITAR_TRASLADO,
            PermissionConstant::CONSULTAR_TRASLADO,
            PermissionConstant::ELIMINAR_TRASLADO,
            PermissionConstant::CREAR_INTERVENCION_TECNICA,
            PermissionConstant::EDITAR_INTERVENCION_TECNICA,
            PermissionConstant::CONSULTAR_INTERVENCION_TECNICA,
            PermissionConstant::ELIMINAR_INTERVENCION_TECNICA,
            PermissionConstant::CREAR_OFICIO,
            PermissionConstant::EDITAR_OFICIO,
            PermissionConstant::CONSULTAR_OFICIO,
            PermissionConstant::ELIMINAR_OFICIO,
            PermissionConstant::CREAR_SIN_EFECTO,
            PermissionConstant::EDITAR_SIN_EFECTO,
            PermissionConstant::CONSULTAR_SIN_EFECTO,
            PermissionConstant::ELIMINAR_SIN_EFECTO,
            PermissionConstant::CONSULTAR_DELITO,
            PermissionConstant::CONSULTAR_TIPO_COLISION,
            PermissionConstant::CONSULTAR_USUARIO,
            PermissionConstant::CONSULTAR_DEPENDENCIA,
            PermissionConstant::CREAR_ESTADO_INFORME_TECNICO,
            PermissionConstant::EDITAR_ESTADO_INFORME_TECNICO,
            PermissionConstant::CONSULTAR_ESTADO_INFORME_TECNICO,
            PermissionConstant::ELIMINAR_ESTADO_INFORME_TECNICO,
            PermissionConstant::CONSULTAR_EXPEDIENTE,
            PermissionConstant::CONSULTAR_PERSONAL,
            PermissionConstant::CONSULTAR_RENAPER
        ]);


        $role = Role::create(['name' => RolConstant::JEFE_DEPENDENCIA]);
        $role->givePermissionTo([
            PermissionConstant::CONSULTAR_NECRO,
            PermissionConstant::CONSULTAR_TRASLADO,
            PermissionConstant::CONSULTAR_INTERVENCION_TECNICA,
            PermissionConstant::CONSULTAR_OFICIO,
            PermissionConstant::CONSULTAR_SIN_EFECTO,
            PermissionConstant::CONSULTAR_USUARIO,
            PermissionConstant::CONSULTAR_DEPENDENCIA,
            PermissionConstant::CONSULTAR_DELITO,
            PermissionConstant::CONSULTAR_TIPO_COLISION,
            PermissionConstant::CONSULTAR_ESTADO_INFORME_TECNICO,
            PermissionConstant::CONSULTAR_EXPEDIENTE,
            PermissionConstant::CONSULTAR_PERSONAL,
            PermissionConstant::CONSULTAR_RENAPER
        ]);


    }
}
