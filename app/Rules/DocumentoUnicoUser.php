<?php

namespace App\Rules;

use App\Models\Documento;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class DocumentoUnicoUser implements Rule
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
        $tipo = $this->params[0];
        $numero = $this->params[1];
        $id = $this->params[2];

        if(!$id){ //NUEVO USUARIO
            $documento = Documento::where('tipo_documento', $tipo)
                ->where('numero', $numero)
                ->first();

            if(!$documento){
                return true;
            } else {
                $usuarios = DB::table('users')
                    ->where('documento_id', $documento->id)
                    ->whereNotNull('deleted_at')
                    ->get();
                if($usuarios->isEmpty()){
                    return true;
                } else {
                    return false;
                }
            }
        } else {  //EDITAR USUARIO
            $documento = Documento::where('tipo_documento', $tipo)
                ->where('numero', $numero)
                ->first();

            if(!$documento){
                return true;
            } else {
                if($documento->id == $id){
                    return true;
                } else {
                    $usuarios = DB::table('users')
                        ->where('documento_id', $documento->id)
                        ->whereNotNull('deleted_at')
                        ->get();
                    if($usuarios->isEmpty()){
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El DNI ingresado ya tiene un Usuario Activo asociado';
    }
}
