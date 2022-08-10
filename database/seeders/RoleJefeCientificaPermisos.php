<?php

namespace Database\Seeders;

use App\Constant\PermissionConstant;
use App\Constant\RolConstant;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleJefeCientificaPermisos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => RolConstant::JEFE_CIENTIFICA]);
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
            PermissionConstant::CONSULTAR_PARA_TODAS_LAS_DEPENDENCIAS,
            PermissionConstant::VER_ESTADISTICAS,
            PermissionConstant::CREAR_NECRO,
            PermissionConstant::CREAR_TRASLADO,
            PermissionConstant::CREAR_INTERVENCION_TECNICA,
            PermissionConstant::CREAR_SIN_EFECTO,
            PermissionConstant::EDITAR_NECRO,
            PermissionConstant::EDITAR_TRASLADO,
            PermissionConstant::EDITAR_INTERVENCION_TECNICA,
            PermissionConstant::EDITAR_SIN_EFECTO,
            PermissionConstant::ELIMINAR_NECRO,
            PermissionConstant::ELIMINAR_TRASLADO,
            PermissionConstant::ELIMINAR_INTERVENCION_TECNICA,
            PermissionConstant::ELIMINAR_SIN_EFECTO,
            PermissionConstant::CREAR_NECRO_VIEJO,
            PermissionConstant::CREAR_TRASLADO_VIEJO,
            PermissionConstant::CREAR_INTERVENCION_TECNICA_VIEJO,
            PermissionConstant::EDITAR_NECRO_VIEJO,
            PermissionConstant::EDITAR_TRASLADO_VIEJO,
            PermissionConstant::EDITAR_INTERVENCION_TECNICA_VIEJO,
            PermissionConstant::EDITAR_SIN_EFECTO_VIEJO,
            PermissionConstant::CREAR_USUARIO,
            PermissionConstant::EDITAR_USUARIO,
            PermissionConstant::ELIMINAR_USUARIO,
            PermissionConstant::CREAR_DEPENDENCIA,
            PermissionConstant::EDITAR_DEPENDENCIA,
            PermissionConstant::ELIMINAR_DEPENDENCIA
        ]);
    }
}
