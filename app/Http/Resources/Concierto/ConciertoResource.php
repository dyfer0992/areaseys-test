<?php

namespace App\Http\Resources\Concierto;

use Illuminate\Http\Resources\Json\JsonResource;

class ConciertoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                  => $this->id,
            'nombre'              => $this->nombre,
            'fecha'               => $this->fecha->toDateTimeString(),
            'promotor'            => new PromotorResource($this->promotor),
            'recinto'             => new RecintoResource($this->recinto),
            'numero_espectadores' => $this->numero_espectadores,
            'rentabilidad'        => $this->rentabilidad,
            'grupos'              => GrupoResource::collection($this->grupos),
            'medios'              => MedioResource::collection($this->medios),
        ];
    }
}
