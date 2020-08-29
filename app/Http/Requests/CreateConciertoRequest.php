<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateConciertoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nombre'              => 'required|string',
            'fecha'               => 'required|date',
            'id_recinto'          => 'required|exists:recintos,id',
            'id_promotor'         => 'required|exists:promotores,id',
            'numero_espectadores' => 'required|integer',
            'id_grupos'           => 'required|array|min:1',
            'id_grupos.*'         => 'exists:grupos,id',
            'id_medios'           => 'required|array|min:1',
            'id_medios.*'         => 'exists:medios,id',
        ];
    }
}
