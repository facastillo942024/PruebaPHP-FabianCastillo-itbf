<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Acomodación de una habitación: Sencilla, Doble, Triple, Cuádruple (catálogo estático).
 *
 * @property int    $id
 * @property string $name
 */
class Accommodation extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * Tipos de habitación compatibles con esta acomodación (matriz de reglas).
     */
    public function roomTypes(): BelongsToMany
    {
        return $this->belongsToMany(
            RoomType::class,
            'room_type_accommodations',
            'accommodation_id',
            'room_type_id'
        );
    }
}
