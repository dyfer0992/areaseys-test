<?php

namespace App\Http\Resources\Concierto;

use Illuminate\Http\Resources\Json\JsonResource;

class RecintoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'nombre'         => $this->nombre,
            'coste_alquiler' => $this->coste_alquiler,
            'precio_entrada' => $this->precio_entrada,
        ];
    }
}
