<?php

namespace App\Domain\Concierto;

use Illuminate\Database\Eloquent\Model;

class Concierto extends Model
{
    protected $table = 'conciertos';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'fecha',
        'id_promotor',
        'id_recinto',
        'numero_espectadores',
        'rentabilidad',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function promotor()
    {
        return $this->belongsTo(Promotor::class, 'id_promotor');
    }

    public function recinto()
    {
        return $this->belongsTo(Recinto::class, 'id_recinto');
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupos_conciertos', 'id_concierto', 'id_grupo');
    }

    public function medios()
    {
        return $this->belongsToMany(Medio::class, 'grupos_medios', 'id_concierto', 'id_medio');
    }

    public function calculateRentabilidad()
    {
        $caches             = $this->grupos->sum('cache');
        $beneficioEntradas  = $this->recinto->precio_entrada * $this->numero_espectadores;

        $gastos = $this->recinto->coste_alquiler + $caches + (($beneficioEntradas * 0.1) * $this->grupos->count());

        $this->rentabilidad = $beneficioEntradas - $gastos;
        $this->save();
    }
}
