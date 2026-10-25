<?php

use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\HotelRoomController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v1 - Sistema de Gestión Hotelera Decameron
|--------------------------------------------------------------------------
| Todas las rutas quedan bajo el prefijo /api (configurado en bootstrap/app.php)
| y se versionan con el segmento v1 => /api/v1/...
*/

Route::prefix('v1')->group(function () {
    // Catálogos (ciudades, tipos, acomodaciones y matriz de reglas)
    Route::get('catalogs', [CatalogController::class, 'index']);

    // Hoteles (CRUD RESTful)
    Route::apiResource('hotels', HotelController::class);

    // Asignación de habitaciones a un hotel
    Route::post('hotels/{hotel}/rooms', [HotelRoomController::class, 'store']);
});
