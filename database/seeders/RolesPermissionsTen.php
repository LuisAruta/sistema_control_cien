<?php

namespace Database\Seeders;

use App\Constant\PermissionConstant;
use App\Constant\RolConstant;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesPermissionsTen extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => RolConstant::OPERADOR_TEN]);
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
            PermissionConstant::CONSULTAR_RENAPER,
            PermissionConstant::CREAR_PARA_TODAS_LAS_DEPENDENCIAS,
            PermissionConstant::CONSULTAR_PARA_TODAS_LAS_DEPENDENCIAS
        ]);


        $role = Role::create(['name' => RolConstant::OFICIAL_DE_SERVICIO_TEN]);
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
            PermissionConstant::CONSULTAR_RENAPER,
            PermissionConstant::CREAR_PARA_TODAS_LAS_DEPENDENCIAS,
            PermissionConstant::CONSULTAR_PARA_TODAS_LAS_DEPENDENCIAS
        ]);

        $role = Role::create(['name' => RolConstant::CONTROL_DE_INFORMES_TEN]);
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
            PermissionConstant::CONSULTAR_RENAPER,
            PermissionConstant::CREAR_PARA_TODAS_LAS_DEPENDENCIAS,
            PermissionConstant::CONSULTAR_PARA_TODAS_LAS_DEPENDENCIAS
        ]);


        $role = Role::create(['name' => RolConstant::JEFE_DEPENDENCIA_TEN]);
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
            PermissionConstant::CONSULTAR_RENAPER,
            PermissionConstant::CONSULTAR_PARA_TODAS_LAS_DEPENDENCIAS
        ]);
    }
}
