<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\City;
use App\Models\RoomType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Expone los catálogos estáticos y la matriz de reglas que el frontend
 * necesita para construir los formularios (ciudades, tipos, acomodaciones).
 */
class CatalogController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => [
                'cities'         => City::orderBy('name')->get(['id', 'name']),
                'room_types'     => RoomType::orderBy('id')->get(['id', 'name']),
                'accommodations' => Accommodation::orderBy('id')->get(['id', 'name']),
                'compatibility'  => DB::table('room_type_accommodations')
                    ->get(['room_type_id', 'accommodation_id']),
            ],
        ]);
    }
}
