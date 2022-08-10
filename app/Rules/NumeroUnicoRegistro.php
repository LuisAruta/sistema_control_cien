<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class NumeroUnicoRegistro implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $numero = DB::table($this->params[0])
            ->where('delegacion_dependencia_id',$this->params[2])
            ->where('id','<>',$this->params[1])
            ->get()->pluck('numero_de_registro')->toArray();
        return !in_array($value,$numero);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El número de registro ingresado ya esta registrado para esa dependencia.';
    }
}
