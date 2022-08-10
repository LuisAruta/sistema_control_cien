<?php

namespace App\Traits;

use App\Constant\PermissionConstant;
use App\Exceptions\ValidationException;
use Auth;

trait ValidationTrait
{
    public function validatePermission($permission)
    {
        $user = Auth::user();
        if (!($user && optional($user)->hasPermissionTo($permission))) {
            throw new ValidationException('Forbidden – You don’t have permission required');
        }
    }

    public function validateDependencia($dependencia)
    {
        $user = Auth::user();
        if (!($user && (optional($user)->checkDependencia($dependencia) || optional($user)->hasPermissionTo(PermissionConstant::CREAR_PARA_TODAS_LAS_DEPENDENCIAS)))) {
            throw new ValidationException('Forbidden – You don’t have dependencia required');
        }
    }

    public function validateDependenciaUsuario()
    {
        $user = Auth::user();
        return $user && optional($user)->hasPermissionTo(PermissionConstant::CONSULTAR_PARA_TODAS_LAS_DEPENDENCIAS);
    }
}
