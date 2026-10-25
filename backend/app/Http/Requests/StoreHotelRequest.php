<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validación para el registro de un hotel.
 *
 * Reglas:
 *   - name y nit únicos (no hoteles repetidos).
 *   - city_id debe existir en el catálogo de ciudades.
 *   - max_rooms entero positivo.
 */
class StoreHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:150', 'unique:hotels,name'],
            'address'   => ['required', 'string', 'max:200'],
            'city_id'   => ['required', 'integer', 'exists:cities,id'],
            'nit'       => ['required', 'string', 'max:20', 'unique:hotels,nit'],
            'max_rooms' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique'      => 'Ya existe un hotel registrado con este nombre.',
            'nit.unique'       => 'Ya existe un hotel registrado con este NIT.',
            'city_id.exists'   => 'La ciudad seleccionada no es válida.',
            'max_rooms.min'    => 'El número máximo de habitaciones debe ser mayor que cero.',
        ];
    }
}
