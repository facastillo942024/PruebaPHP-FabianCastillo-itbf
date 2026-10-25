<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Catálogo estático de ciudades.
 */
class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['id' => 1, 'name' => 'CARTAGENA'],
            ['id' => 2, 'name' => 'SAN ANDRES'],
            ['id' => 3, 'name' => 'SANTA MARTA'],
            ['id' => 4, 'name' => 'BOGOTA'],
            ['id' => 5, 'name' => 'MEDELLIN'],
            ['id' => 6, 'name' => 'CALI'],
        ];

        DB::table('cities')->upsert(
            array_map(fn ($c) => $c + ['created_at' => now()], $cities),
            ['id'],
            ['name']
        );

        // Mantener la secuencia de IDs sincronizada tras insertar IDs explícitos.
        DB::statement("SELECT setval(pg_get_serial_sequence('cities', 'id'), (SELECT MAX(id) FROM cities))");
    }
}
