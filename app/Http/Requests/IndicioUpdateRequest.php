<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndicioUpdateRequest extends FormRequest
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
            'cantidad' => ['required', 'integer'],
            'detalle' => ['string', 'max:80'],
            'tipo_indicio_id' => ['required', 'integer', 'exists:tipo_indicio,id']
        ];
    }
}
