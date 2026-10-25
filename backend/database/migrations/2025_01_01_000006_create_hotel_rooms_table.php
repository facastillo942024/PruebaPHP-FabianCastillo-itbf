<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Asignación de habitaciones por hotel.
 *
 * Reglas de integridad:
 *   - quantity debe ser mayor que cero.
 *   - No se permite repetir la combinación (hotel, tipo, acomodación):
 *     garantizado por el índice único unique_hotel_room_accommodation.
 *   - El límite de suma de quantity vs max_rooms se valida en la capa de dominio
 *     (RoomAssignmentService) porque requiere un cálculo agregado por hotel.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')
                ->constrained('hotels')
                ->cascadeOnDelete();
            $table->foreignId('room_type_id')->constrained('room_types');
            $table->foreignId('accommodation_id')->constrained('accommodations');
            $table->unsignedInteger('quantity');
            $table->timestamps();

            $table->unique(
                ['hotel_id', 'room_type_id', 'accommodation_id'],
                'unique_hotel_room_accommodation'
            );
        });

        // CHECK a nivel de base de datos: quantity > 0
        DB::statement('ALTER TABLE hotel_rooms ADD CONSTRAINT hotel_rooms_quantity_check CHECK (quantity > 0)');
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_rooms');
    }
};
