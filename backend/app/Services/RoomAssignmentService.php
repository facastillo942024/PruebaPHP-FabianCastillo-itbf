<?php

namespace App\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Repositories\Contracts\RoomAssignmentRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Reglas de negocio para la asignación de habitaciones a un hotel.
 *
 * Centraliza las cuatro reglas de integridad del enunciado:
 *   1. La combinación (tipo, acomodación) debe estar permitida por la matriz.
 *   2. No se permite repetir la combinación (tipo, acomodación) en el hotel.
 *   3. La suma de cantidades no puede superar max_rooms del hotel.
 *   4. quantity debe ser positivo (reforzado además por CHECK en la BD).
 */
class RoomAssignmentService
{
    public function __construct(
        private readonly RoomAssignmentRepositoryInterface $assignments
    ) {
    }

    /**
     * Asigna una cantidad de habitaciones de un tipo/acomodación a un hotel,
     * validando todas las reglas de negocio de forma atómica.
     *
     * @throws BusinessRuleException
     */
    public function assign(Hotel $hotel, array $data): HotelRoom
    {
        $roomTypeId      = (int) $data['room_type_id'];
        $accommodationId = (int) $data['accommodation_id'];
        $quantity        = (int) $data['quantity'];

        return DB::transaction(function () use ($hotel, $roomTypeId, $accommodationId, $quantity) {
            // Bloquea la fila del hotel para evitar condiciones de carrera
            // al calcular la suma acumulada de habitaciones.
            $hotel = Hotel::whereKey($hotel->getKey())->lockForUpdate()->firstOrFail();

            $this->guardCompatibility($roomTypeId, $accommodationId);
            $this->guardNoDuplicate($hotel, $roomTypeId, $accommodationId);
            $this->guardMaxRooms($hotel, $quantity);

            return $this->assignments->create([
                'hotel_id'         => $hotel->id,
                'room_type_id'     => $roomTypeId,
                'accommodation_id' => $accommodationId,
                'quantity'         => $quantity,
            ]);
        });
    }

    /**
     * Regla 1: la acomodación debe corresponder al tipo de habitación.
     */
    private function guardCompatibility(int $roomTypeId, int $accommodationId): void
    {
        if (! $this->assignments->isCompatible($roomTypeId, $accommodationId)) {
            throw new BusinessRuleException(
                'La acomodación seleccionada no es válida para el tipo de habitación.',
                'accommodation_id'
            );
        }
    }

    /**
     * Regla 2: no repetir la combinación (tipo, acomodación) en el mismo hotel.
     */
    private function guardNoDuplicate(Hotel $hotel, int $roomTypeId, int $accommodationId): void
    {
        if ($this->assignments->combinationExists($hotel->id, $roomTypeId, $accommodationId)) {
            throw new BusinessRuleException(
                'Esta combinación de tipo de habitación y acomodación ya está registrada para el hotel.',
                'room_type_id'
            );
        }
    }

    /**
     * Regla 3: la suma de habitaciones no puede superar el máximo del hotel.
     */
    private function guardMaxRooms(Hotel $hotel, int $quantity): void
    {
        $alreadyAssigned = $this->assignments->totalAssignedForHotel($hotel);

        if (($alreadyAssigned + $quantity) > $hotel->max_rooms) {
            $available = max(0, $hotel->max_rooms - $alreadyAssigned);

            throw new BusinessRuleException(
                "La cantidad supera el máximo de habitaciones del hotel. "
                . "Disponibles: {$available} de {$hotel->max_rooms}.",
                'quantity'
            );
        }
    }
}
