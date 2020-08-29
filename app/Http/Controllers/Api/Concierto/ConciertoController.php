<?php

namespace App\Http\Controllers\Api\Concierto;

use App\Domain\Concierto\Concierto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateConciertoRequest;
use App\Http\Resources\Concierto\ConciertoResource;
use App\Notifications\InfoToPromotor;

class ConciertoController extends Controller
{
    public function createConcierto(CreateConciertoRequest $request)
    {
        $concierto = Concierto::create($request->validated());
        $concierto->grupos()->sync($request->id_grupos);
        $concierto->medios()->sync($request->id_medios);

        $concierto->calculateRentabilidad();

        $concierto->promotor->notify(new InfoToPromotor($concierto));

        return new ConciertoResource($concierto);
    }
}
