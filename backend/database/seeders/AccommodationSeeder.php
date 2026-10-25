<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Catálogo estático de acomodaciones.
 */
class AccommodationSeeder extends Seeder
{
    public function run(): void
    {
        $accommodations = [
            ['id' => 1, 'name' => 'SENCILLA'],
            ['id' => 2, 'name' => 'DOBLE'],
            ['id' => 3, 'name' => 'TRIPLE'],
            ['id' => 4, 'name' => 'CUADRUPLE'],
        ];

        DB::table('accommodations')->upsert($accommodations, ['id'], ['name']);

        DB::statement("SELECT setval(pg_get_serial_sequence('accommodations', 'id'), (SELECT MAX(id) FROM accommodations))");
    }
}
