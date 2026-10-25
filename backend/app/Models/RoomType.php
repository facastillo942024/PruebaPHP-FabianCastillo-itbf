<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Tipo de habitación: Estándar, Junior, Suite (catálogo estático).
 *
 * @property int    $id
 * @property string $name
 */
class RoomType extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * Acomodaciones compatibles con este tipo de habitación (matriz de reglas).
     */
    public function accommodations(): BelongsToMany
    {
        return $this->belongsToMany(
            Accommodation::class,
            'room_type_accommodations',
            'room_type_id',
            'accommodation_id'
        );
    }
}
