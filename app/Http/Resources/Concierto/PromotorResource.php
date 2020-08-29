<?php

namespace App\Http\Resources\Concierto;

use Illuminate\Http\Resources\Json\JsonResource;

class PromotorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'nombre' => $this->nombre,
        ];
    }
}
