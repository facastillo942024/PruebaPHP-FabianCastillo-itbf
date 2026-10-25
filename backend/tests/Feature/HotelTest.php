<?php

namespace Tests\Feature;

use App\Models\Hotel;
use Database\Seeders\CitySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas del CRUD de hoteles y sus reglas de unicidad.
 */
class HotelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CitySeeder::class);
    }

    public function test_registra_un_hotel_valido(): void
    {
        $response = $this->postJson('/api/v1/hotels', [
            'name'      => 'DECAMERON CARTAGENA',
            'address'   => 'CALLE 23 58-25',
            'city_id'   => 1,
            'nit'       => '12345678-9',
            'max_rooms' => 42,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'DECAMERON CARTAGENA')
            ->assertJsonPath('data.available_rooms', 42);

        $this->assertDatabaseHas('hotels', ['nit' => '12345678-9']);
    }

    public function test_rechaza_hotel_con_nombre_o_nit_duplicado(): void
    {
        Hotel::factory()->create([
            'name' => 'DECAMERON CARTAGENA',
            'nit'  => '12345678-9',
        ]);

        $response = $this->postJson('/api/v1/hotels', [
            'name'      => 'DECAMERON CARTAGENA',
            'address'   => 'OTRA DIRECCION',
            'city_id'   => 1,
            'nit'       => '12345678-9',
            'max_rooms' => 10,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'nit']);
    }

    public function test_lista_hoteles_con_sus_habitaciones(): void
    {
        Hotel::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/hotels');

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    ['id', 'name', 'nit', 'max_rooms', 'rooms', 'assigned_rooms', 'available_rooms'],
                ],
            ]);
    }
}
