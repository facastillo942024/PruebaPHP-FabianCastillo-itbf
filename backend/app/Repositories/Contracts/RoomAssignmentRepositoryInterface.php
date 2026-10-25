<?php

namespace App\Repositories\Contracts;

use App\Models\Hotel;
use App\Models\HotelRoom;

/**
 * Contrato de persistencia para la asignación de habitaciones a hoteles.
 */
interface RoomAssignmentRepositoryInterface
{
    public function create(array $data): HotelRoom;

    /**
     * ¿Existe ya la combinación (hotel, tipo, acomodación)?
     */
    public function combinationExists(int $hotelId, int $roomTypeId, int $accommodationId): bool;

    /**
     * ¿La combinación (tipo, acomodación) está permitida por la matriz de reglas?
     */
    public function isCompatible(int $roomTypeId, int $accommodationId): bool;

    /**
     * Total de habitaciones ya asignadas a un hotel.
     */
    public function totalAssignedForHotel(Hotel $hotel): int;
}
