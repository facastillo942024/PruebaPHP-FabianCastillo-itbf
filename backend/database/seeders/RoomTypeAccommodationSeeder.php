<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Matriz de compatibilidad Tipo de Habitación <-> Acomodación.
 *
 *   ESTANDAR (1) -> SENCILLA (1), DOBLE (2)
 *   JUNIOR   (2) -> TRIPLE (3), CUADRUPLE (4)
 *   SUITE    (3) -> SENCILLA (1), DOBLE (2), TRIPLE (3)
 */
class RoomTypeAccommodationSeeder extends Seeder
{
    public function run(): void
    {
        $matrix = [
            ['room_type_id' => 1, 'accommodation_id' => 1], // ESTANDAR -> SENCILLA
            ['room_type_id' => 1, 'accommodation_id' => 2], // ESTANDAR -> DOBLE
            ['room_type_id' => 2, 'accommodation_id' => 3], // JUNIOR   -> TRIPLE
            ['room_type_id' => 2, 'accommodation_id' => 4], // JUNIOR   -> CUADRUPLE
            ['room_type_id' => 3, 'accommodation_id' => 1], // SUITE    -> SENCILLA
            ['room_type_id' => 3, 'accommodation_id' => 2], // SUITE    -> DOBLE
            ['room_type_id' => 3, 'accommodation_id' => 3], // SUITE    -> TRIPLE
        ];

        DB::table('room_type_accommodations')->upsert(
            $matrix,
            ['room_type_id', 'accommodation_id'],
            []
        );
    }
}
