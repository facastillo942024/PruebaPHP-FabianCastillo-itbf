<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Ciudad donde se ubica un hotel (catálogo estático).
 *
 * @property int    $id
 * @property string $name
 */
class City extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * Hoteles ubicados en esta ciudad.
     */
    public function hotels(): HasMany
    {
        return $this->hasMany(Hotel::class);
    }
}
