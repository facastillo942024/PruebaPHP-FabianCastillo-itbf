<?php

namespace Tests\Feature;

use App\Models\Hotel;
use Database\Seeders\AccommodationSeeder;
use Database\Seeders\CitySeeder;
use Database\Seeders\RoomTypeAccommodationSeeder;
use Database\Seeders\RoomTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas de integración de las reglas de negocio de asignación de habitaciones.
 *
 * Cubre los cuatro casos exigidos por la especificación:
 *   1. Inserción exitosa cuando la acomodación corresponde al tipo.
 *   2. Rechazo (422) cuando tipo y acomodación son incompatibles.
 *   3. Rechazo (422) cuando la suma acumulada supera max_rooms.
 *   4. Rechazo (422) de combinaciones (tipo, acomodación) duplicadas.
 */
class HotelRoomTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Catálogos estáticos necesarios para las reglas de negocio.
        $this->seed([
            CitySeeder::class,
            RoomTypeSeeder::class,
            AccommodationSeeder::class,
            RoomTypeAccommodationSeeder::class,
        ]);
    }

    private function createHotel(int $maxRooms = 42): Hotel
    {
        return Hotel::factory()->create(['max_rooms' => $maxRooms]);
    }

    /** ESTANDAR + SENCILLA es compatible => 201 Created. */
    public function test_asigna_habitacion_cuando_la_acomodacion_corresponde_al_tipo(): void
    {
        $hotel = $this->createHotel();

        $response = $this->postJson("/api/v1/hotels/{$hotel->id}/rooms", [
            'room_type_id'     => 1, // ESTANDAR
            'accommodation_id' => 1, // SENCILLA
            'quantity'         => 10,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.quantity', 10)
            ->assertJsonPath('data.room_type', 'ESTANDAR')
            ->assertJsonPath('data.accommodation', 'SENCILLA');

        $this->assertDatabaseHas('hotel_rooms', [
            'hotel_id'         => $hotel->id,
            'room_type_id'     => 1,
            'accommodation_id' => 1,
            'quantity'         => 10,
        ]);
    }

    /** ESTANDAR + TRIPLE no está en la matriz => 422. */
    public function test_rechaza_asignacion_incompatible_entre_tipo_y_acomodacion(): void
    {
        $hotel = $this->createHotel();

        $response = $this->postJson("/api/v1/hotels/{$hotel->id}/rooms", [
            'room_type_id'     => 1, // ESTANDAR
            'accommodation_id' => 3, // TRIPLE (no permitida para ESTANDAR)
            'quantity'         => 5,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('accommodation_id');

        $this->assertDatabaseCount('hotel_rooms', 0);
    }

    /** La suma de cantidades supera max_rooms => 422. */
    public function test_rechaza_asignacion_que_supera_el_maximo_de_habitaciones(): void
    {
        $hotel = $this->createHotel(maxRooms: 30);

        // Primera asignación válida (20 de 30).
        $this->postJson("/api/v1/hotels/{$hotel->id}/rooms", [
            'room_type_id'     => 1,
            'accommodation_id' => 1,
            'quantity'         => 20,
        ])->assertCreated();

        // Segunda asignación pediría 15 más (total 35 > 30) => rechazada.
        $response = $this->postJson("/api/v1/hotels/{$hotel->id}/rooms", [
            'room_type_id'     => 1,
            'accommodation_id' => 2, // DOBLE (compatible con ESTANDAR)
            'quantity'         => 15,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('quantity');

        // Solo la primera asignación debe persistir.
        $this->assertDatabaseCount('hotel_rooms', 1);
    }

    /** Repetir la combinación (tipo, acomodación) en el mismo hotel => 422. */
    public function test_rechaza_combinacion_duplicada_en_el_mismo_hotel(): void
    {
        $hotel = $this->createHotel();

        $payload = [
            'room_type_id'     => 3, // SUITE
            'accommodation_id' => 3, // TRIPLE (compatible)
            'quantity'         => 5,
        ];

        $this->postJson("/api/v1/hotels/{$hotel->id}/rooms", $payload)->assertCreated();

        $response = $this->postJson("/api/v1/hotels/{$hotel->id}/rooms", $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('room_type_id');

        $this->assertDatabaseCount('hotel_rooms', 1);
    }
}
