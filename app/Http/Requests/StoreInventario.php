<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventario extends FormRequest
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
            'cantidad' => 'required|min:1|max:3',
            'lote' => 'min:1',
            'vencimiento' => 'date|date_format:Y-m-d',
            'articulo_id' => 'required',
            'supplier_id' => 'nullable'
        ];
    }
}
