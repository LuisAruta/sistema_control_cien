<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class OficioStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'oficio.fecha_ingreso' => ['required'],
            'oficio.instancia_solicitante_id' => ['required', 'integer', 'exists:dependencias,id'],
            'oficio.observacion' => ['required', 'string', 'max:400'],
            'oficio.oficiable_id' => ['required'],
            'oficio.estado_oficio_id' => ['required'],
            'oficio.estado_id' => ['required']
        ];
    }
}
