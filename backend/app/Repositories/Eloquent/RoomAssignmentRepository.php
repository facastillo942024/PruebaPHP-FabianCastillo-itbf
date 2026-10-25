<?php

namespace App\Repositories\Eloquent;

use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Repositories\Contracts\RoomAssignmentRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Implementación Eloquent del repositorio de asignación de habitaciones.
 */
class RoomAssignmentRepository implements RoomAssignmentRepositoryInterface
{
    public function create(array $data): HotelRoom
    {
        return HotelRoom::create($data)
            ->load(['roomType', 'accommodation']);
    }

    public function combinationExists(int $hotelId, int $roomTypeId, int $accommodationId): bool
    {
        return HotelRoom::where('hotel_id', $hotelId)
            ->where('room_type_id', $roomTypeId)
            ->where('accommodation_id', $accommodationId)
            ->exists();
    }

    public function isCompatible(int $roomTypeId, int $accommodationId): bool
    {
        return DB::table('room_type_accommodations')
            ->where('room_type_id', $roomTypeId)
            ->where('accommodation_id', $accommodationId)
            ->exists();
    }

    public function totalAssignedForHotel(Hotel $hotel): int
    {
        return (int) $hotel->rooms()->sum('quantity');
    }
}
