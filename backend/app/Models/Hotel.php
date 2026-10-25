<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Hotel de la compañía con sus datos tributarios básicos.
 *
 * @property int    $id
 * @property string $name
 * @property string $address
 * @property int    $city_id
 * @property string $nit
 * @property int    $max_rooms
 */
class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city_id',
        'nit',
        'max_rooms',
    ];

    protected $casts = [
        'max_rooms' => 'integer',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Habitaciones asignadas a este hotel.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(HotelRoom::class);
    }

    /**
     * Total de habitaciones ya asignadas al hotel (suma de cantidades).
     * Útil para validar el límite contra max_rooms.
     */
    public function assignedRoomsCount(): int
    {
        return (int) $this->rooms()->sum('quantity');
    }
}
