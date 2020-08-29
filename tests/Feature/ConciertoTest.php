<?php

namespace Tests\Feature;

use App\Domain\Concierto\Grupo;
use App\Domain\Concierto\Medio;
use App\Domain\Concierto\Promotor;
use App\Domain\Concierto\Recinto;
use App\Notifications\InfoToPromotor;
use Tests\TestCase;

class ConciertoTest extends TestCase
{
    protected $data;

    protected $recinto;

    protected $promotor;

    protected $grupos;

    protected $medios;

    protected $rentabilidad;

    protected function setUp(): void
    {
        parent::setUp();

        $this->recinto  = factory(Recinto::class)->create();
        $this->promotor = factory(Promotor::class)->create();
        $this->grupos   = factory(Grupo::class)->times(3)->create();
        $this->medios   = factory(Medio::class)->times(2)->create();

        $this->data = [
            'nombre'              => 'Viña rock',
            'fecha'               => now()->addMonths(9)->toDateTimeString(),
            'id_recinto'          => $this->recinto->id,
            'id_promotor'         => $this->promotor->id,
            'numero_espectadores' => 2,
            'id_grupos'           => $this->grupos->pluck('id'),
            'id_medios'           => $this->medios->pluck('id'),
        ];

        $beneficioEntradas  = $this->recinto->precio_entrada * $this->data['numero_espectadores'];
        $caches             = $this->grupos->sum('cache');
        $gastos             = $this->recinto->coste_alquiler + $caches + (($beneficioEntradas * 0.1) * $this->grupos->count());
        $this->rentabilidad = $beneficioEntradas - $gastos;

        \Notification::fake();
    }

    /** @test */
    public function cannot_create_concierto_without_data()
    {
        $this->json('POST', '/api/conciertos', [])
            ->assertStatus(422);
    }

    /** @test */
    public function cannot_create_concierto_without_recinto_info()
    {
        unset($this->data['id_recinto']);
        $this->json('POST', '/api/conciertos', $this->data)
            ->assertStatus(422);
    }

    /** @test */
    public function cannot_create_concierto_without_recinto_error()
    {
        $this->data['id_recinto'] = 1000;
        $this->json('POST', '/api/conciertos', $this->data)
            ->assertStatus(422);
    }

    /** @test */
    public function cannot_create_concierto_without_promotor_info()
    {
        unset($this->data['id_promotor']);
        $this->json('POST', '/api/conciertos', $this->data)
            ->assertStatus(422);
    }

    /** @test */
    public function cannot_create_concierto_without_promotor_error()
    {
        $this->data['id_promotor'] = 2000;
        $this->json('POST', '/api/conciertos', $this->data)
            ->assertStatus(422);
    }

    /** @test */
    public function cannot_create_concierto_without_nombre()
    {
        unset($this->data['nombre']);
        $this->json('POST', '/api/conciertos', $this->data)
            ->assertStatus(422);
    }

    /** @test */
    public function cannot_create_concierto_without_fecha()
    {
        unset($this->data['fecha']);
        $this->json('POST', '/api/conciertos', $this->data)
            ->assertStatus(422);
    }

    /** @test */
    public function cannot_create_concierto_without_grupos_info()
    {
        unset($this->data['id_grupos']);
        $this->json('POST', '/api/conciertos', $this->data)
            ->assertStatus(422);
        $this->data['id_grupos'] = [];
        $this->json('POST', '/api/conciertos', $this->data)
            ->assertStatus(422);
    }

    /** @test */
    public function cannot_create_concierto_without_medios_info()
    {
        unset($this->data['id_medios']);
        $this->json('POST', '/api/conciertos', $this->data)
            ->assertStatus(422);
        $this->data['id_medios'] = [];
        $this->json('POST', '/api/conciertos', $this->data)
            ->assertStatus(422);
    }

    /** @test */
    public function can_create_concierto()
    {
        $response = $this->json('POST', '/api/conciertos', $this->data)
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'nombre'              => $this->data['nombre'],
                    'numero_espectadores' => $this->data['numero_espectadores'],
                    'recinto'             => [
                        'id' => $this->recinto->id,
                    ],
                    'promotor' => [
                        'id' => $this->promotor->id,
                    ],
                ]
            ]);

        $this->assertDatabaseHas('conciertos', [
            'nombre'              => $this->data['nombre'],
            'numero_espectadores' => $this->data['numero_espectadores'],
            'id_recinto'          => $this->recinto->id,
            'id_promotor'         => $this->promotor->id,
            'rentabilidad'        => $this->rentabilidad,
        ]);

        $conciertoId = $response->decodeResponseJson('data')['id'];

        $this->grupos->each(function ($grupo) use ($conciertoId) {
            $this->assertDatabaseHas('grupos_conciertos', [
                'id_grupo'     => $grupo->id,
                'id_concierto' => $conciertoId,
            ]);
        });

        $this->medios->each(function ($medio) use ($conciertoId) {
            $this->assertDatabaseHas('grupos_medios', [
                'id_medio'     => $medio->id,
                'id_concierto' => $conciertoId,
            ]);
        });

        \Notification::assertSentTo($this->promotor, InfoToPromotor::class);
    }
}
