<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación de formato para la asignación de habitaciones.
 *
 * Aquí solo se validan tipos y existencia en catálogos. Las reglas de negocio
 * (compatibilidad, no duplicidad y límite de max_rooms) viven en
 * RoomAssignmentService, porque requieren el contexto del hotel.
 */
class StoreHotelRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_type_id'     => ['required', 'integer', 'exists:room_types,id'],
            'accommodation_id' => ['required', 'integer', 'exists:accommodations,id'],
            'quantity'         => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'room_type_id.exists'     => 'El tipo de habitación seleccionado no es válido.',
            'accommodation_id.exists' => 'La acomodación seleccionada no es válida.',
            'quantity.min'            => 'La cantidad debe ser mayor que cero.',
        ];
    }
}
