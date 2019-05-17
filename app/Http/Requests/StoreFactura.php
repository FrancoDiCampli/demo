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
            'ptoventa' => 'required|min:1',
            'numfactura' => 'required|min:1',
            'cuit' => 'nullable|min:1',
            'fecha' => 'required|min:1',
            'bonificacion' => 0,
            // 'recargo' => 'required|min:1',
            // 'subtotal' => 'required|min:1',
            // 'total' => 'required|min:1',
            'comprobanteafip' => 'nullable|min:1',
            'cae' => 'nullable|min:1',
            'fechavto' => 'nullable|min:1',
            'codbarra' => 'nullable|min:1',
            'cliente_id' => 'required|min:1',
            'user_id' => 'required|min:1'
        ];
    }
}
