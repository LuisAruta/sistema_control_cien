<?php

namespace App\Rules;

use App\Constant\PermissionConstant;
use Auth;
use Illuminate\Contracts\Validation\Rule;

class DelegacionUser implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = Auth::user();
        return ($user && (optional($user)->checkDependencia($value) || optional($user)->hasPermissionTo(PermissionConstant::CONSULTAR_PARA_TODAS_LAS_DEPENDENCIAS)));

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La dependencia del usuario es dintinta a la dependencia del hecho.';
    }
}
