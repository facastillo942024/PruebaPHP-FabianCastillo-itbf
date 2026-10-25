<?php

namespace App\Http\Resources;

use App\Models\HotelRoom;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Representación JSON de una asignación de habitaciones.
 *
 * @mixin HotelRoom
 */
class RoomAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'hotel_id'         => $this->hotel_id,
            'room_type_id'     => $this->room_type_id,
            'accommodation_id' => $this->accommodation_id,
            'room_type'        => $this->whenLoaded('roomType', fn () => $this->roomType->name),
            'accommodation'    => $this->whenLoaded('accommodation', fn () => $this->accommodation->name),
            'quantity'         => $this->quantity,
        ];
    }
}
