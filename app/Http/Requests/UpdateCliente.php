<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCliente extends FormRequest
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
            'razonsocial' => 'required|min:1|max:190',
            'documentounico' => 'required|min:8|max:11|',
            'direccion' => 'required|min:1|max:190',
            'telefono' => 'min:6|max:13|nullable',
            'email' => 'email|nullable',
            'codigopostal' => 'required|min:4|max:4',
            'localidad' => 'required|min:1|max:190',
            'provincia' => 'required|min:1|max:190',
            'condicioniva' => 'required|min:1|max:190',
            'foto' => 'nullable'
        ];
    }
}
