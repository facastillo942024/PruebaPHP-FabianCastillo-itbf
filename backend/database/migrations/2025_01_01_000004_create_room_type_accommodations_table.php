<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Matriz de compatibilidad Tipo de Habitación <-> Acomodación.
 *
 * Regla de negocio (precargada como datos, no como código):
 *   - Estándar -> Sencilla | Doble
 *   - Junior   -> Triple | Cuádruple
 *   - Suite    -> Sencilla | Doble | Triple
 *
 * Esta tabla permite validar las combinaciones permitidas sin hardcodear
 * la matriz en el dominio, cumpliendo el principio Open/Closed.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_type_accommodations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')
                ->constrained('room_types')
                ->cascadeOnDelete();
            $table->foreignId('accommodation_id')
                ->constrained('accommodations')
                ->cascadeOnDelete();

            $table->unique(['room_type_id', 'accommodation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_type_accommodations');
    }
};
