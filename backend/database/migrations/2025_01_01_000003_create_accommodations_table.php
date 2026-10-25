<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catálogo de acomodaciones: Sencilla, Doble, Triple, Cuádruple.
 * Datos estáticos precargados vía Seeders.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accommodations');
    }
};
