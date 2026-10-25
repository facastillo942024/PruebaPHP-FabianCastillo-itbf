<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catálogo de tipos de habitación: Estándar, Junior, Suite.
 * Datos estáticos precargados vía Seeders.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
