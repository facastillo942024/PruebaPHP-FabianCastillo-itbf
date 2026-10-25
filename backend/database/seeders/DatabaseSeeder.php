<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Orquesta la carga de los catálogos estáticos requeridos por el sistema.
 *
 * El orden importa: primero los catálogos base (ciudades, tipos, acomodaciones),
 * luego la matriz de compatibilidad que los referencia.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CitySeeder::class,
            RoomTypeSeeder::class,
            AccommodationSeeder::class,
            RoomTypeAccommodationSeeder::class,
        ]);
    }
}
