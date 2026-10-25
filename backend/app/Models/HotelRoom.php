<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Asignación concreta de habitaciones de un tipo/acomodación a un hotel.
 *
 * @property int $id
 * @property int $hotel_id
 * @property int $room_type_id
 * @property int $accommodation_id
 * @property int $quantity
 */
class HotelRoom extends Model
{
    protected $fillable = [
        'hotel_id',
        'room_type_id',
        'accommodation_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class);
    }
}
