<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class DelitoporTipoIntervencion implements Rule
{
    private $params;

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
        $numero = DB::table('delito_tipo_intervencion_tecnica')
            ->where('delito_id',$value)
            ->where('tipo_intervencion_tecnica_id',$this->params[0])
            ->get();
        return !$numero->isEmpty();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El tipo de Delito no corresponde con el Tipo de Intervención Tecnica.';
    }
}
