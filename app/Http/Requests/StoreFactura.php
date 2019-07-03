<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFactura extends FormRequest
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
            'ptoventa' => 'required',
            'numfactura' => 'required',
            'cuit' => 'nullable',
            'fecha' => 'required',
            // 'bonificacion' => 0,
            // 'recargo' => 'required|min:1',
            'subtotal' => 'required',
            'total' => 'required',
            'comprobanteafip' => 'nullable',
            'cae' => 'nullable',
            'fechavto' => 'nullable',
            'codbarra' => 'nullable',
            'cliente_id' => 'required',
            'user_id' => 'required'
        ];
    }
}
