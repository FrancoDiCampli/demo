<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticulo extends FormRequest
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
            'codarticulo'=>'nullable|min:1|max:10|unique:articulos,codarticulo,'.$this->articulo,
            'articulo'=>'required|min:1|unique:articulos,articulo,'.$this->articulo,
            'descripcion' => 'required|min:1|max:190',
            'medida' => 'required|min:1|max:190',
            'costo' => 'required|min:1',
            'utilidades' => 'required|min:1',
            'precio' => 'required|min:1',
            'alicuota' => 'required|min:1',
            'estado' => 'required|min:1',
            'marca_id' => 'required|min:1',
            'categoria_id' => 'required|min:1',
        ];
    }
}