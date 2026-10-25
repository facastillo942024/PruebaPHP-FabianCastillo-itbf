<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validación para la actualización de un hotel.
 *
 * Igual que el registro, pero las reglas de unicidad ignoran el propio hotel.
 */
class UpdateHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $hotelId = $this->route('hotel')?->id;

        return [
            'name'      => ['required', 'string', 'max:150', Rule::unique('hotels', 'name')->ignore($hotelId)],
            'address'   => ['required', 'string', 'max:200'],
            'city_id'   => ['required', 'integer', 'exists:cities,id'],
            'nit'       => ['required', 'string', 'max:20', Rule::unique('hotels', 'nit')->ignore($hotelId)],
            'max_rooms' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique'    => 'Ya existe un hotel registrado con este nombre.',
            'nit.unique'     => 'Ya existe un hotel registrado con este NIT.',
            'city_id.exists' => 'La ciudad seleccionada no es válida.',
            'max_rooms.min'  => 'El número máximo de habitaciones debe ser mayor que cero.',
        ];
    }
}
