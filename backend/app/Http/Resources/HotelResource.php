<?php

namespace App\Http\Resources;

use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Representación JSON de un hotel con sus habitaciones asignadas.
 *
 * @mixin Hotel
 */
class HotelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'address'         => $this->address,
            'nit'             => $this->nit,
            'max_rooms'       => $this->max_rooms,
            'city'            => $this->whenLoaded('city', fn () => [
                'id'   => $this->city->id,
                'name' => $this->city->name,
            ]),
            'city_id'         => $this->city_id,
            'rooms'           => RoomAssignmentResource::collection($this->whenLoaded('rooms')),
            'assigned_rooms'  => $this->whenLoaded('rooms', fn () => (int) $this->rooms->sum('quantity')),
            'available_rooms' => $this->whenLoaded('rooms', fn () => $this->max_rooms - (int) $this->rooms->sum('quantity')),
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
