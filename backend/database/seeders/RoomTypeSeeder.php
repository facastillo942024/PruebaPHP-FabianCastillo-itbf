<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Catálogo estático de tipos de habitación.
 */
class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['id' => 1, 'name' => 'ESTANDAR'],
            ['id' => 2, 'name' => 'JUNIOR'],
            ['id' => 3, 'name' => 'SUITE'],
        ];

        DB::table('room_types')->upsert($types, ['id'], ['name']);

        DB::statement("SELECT setval(pg_get_serial_sequence('room_types', 'id'), (SELECT MAX(id) FROM room_types))");
    }
}
