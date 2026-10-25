<?php

namespace App\Repositories\Eloquent;

use App\Models\Hotel;
use App\Repositories\Contracts\HotelRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Implementación Eloquent del repositorio de hoteles.
 */
class HotelRepository implements HotelRepositoryInterface
{
    /**
     * Relaciones cargadas por defecto para evitar el problema N+1.
     */
    private const EAGER = ['city', 'rooms.roomType', 'rooms.accommodation'];

    public function all(): Collection
    {
        return Hotel::with(self::EAGER)->orderBy('name')->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Hotel::with(self::EAGER)->orderBy('name')->paginate($perPage);
    }

    public function findById(int $id): ?Hotel
    {
        return Hotel::with(self::EAGER)->find($id);
    }

    public function create(array $data): Hotel
    {
        $hotel = Hotel::create($data);

        return $hotel->load(self::EAGER);
    }

    public function update(Hotel $hotel, array $data): Hotel
    {
        $hotel->update($data);

        return $hotel->load(self::EAGER);
    }

    public function delete(Hotel $hotel): bool
    {
        return (bool) $hotel->delete();
    }
}
