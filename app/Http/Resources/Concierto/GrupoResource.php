<?php

namespace App\Http\Resources\Concierto;

use Illuminate\Http\Resources\Json\JsonResource;

class GrupoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'nombre' => $this->nombre,
            'cache'  => $this->cache,
        ];
    }
}
