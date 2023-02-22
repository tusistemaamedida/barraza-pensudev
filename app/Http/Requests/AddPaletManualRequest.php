<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddPaletManualRequest extends FormRequest
{
    public function authorize(){
        return true;
    }
    public function rules(){
        return [
            'articulo_id' => ['required'],
            'lote' => ['required'],
            'cantidad' => ['required'],
            'kilos' => ['required'],
            'fecha_elaboracion' => ['required'],
        ];
    }

    public function messages(){
        return [
            'articulo_id.required'   => 'Debe seleccionar un artículo!',
            'lote.required'   => 'Ingrese un lote!',
            'cantidad.required'   => 'Ingrese la cantidad!',
            'kilos.required'   => 'Ingrese los kilos totales!',
            'fecha_elaboracion.required'   => 'Ingrese la fecha de elaboración!'
        ];
    }
}
