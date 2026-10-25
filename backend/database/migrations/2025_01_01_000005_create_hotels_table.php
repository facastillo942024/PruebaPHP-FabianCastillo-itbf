<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Tabla principal de hoteles con sus datos tributarios básicos.
 *
 * Reglas de integridad:
 *   - name y nit son únicos (no pueden existir hoteles repetidos).
 *   - max_rooms debe ser mayor que cero.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->string('address', 200);
            $table->foreignId('city_id')->constrained('cities');
            $table->string('nit', 20)->unique();
            $table->unsignedInteger('max_rooms');
            $table->timestamps();
        });

        // CHECK a nivel de base de datos: max_rooms > 0
        DB::statement('ALTER TABLE hotels ADD CONSTRAINT hotels_max_rooms_check CHECK (max_rooms > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
