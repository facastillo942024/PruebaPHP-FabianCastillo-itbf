<?php

namespace App\Services;

use App\Models\Hotel;
use App\Repositories\Contracts\HotelRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Casos de uso relacionados con la gestión de hoteles.
 *
 * Depende de la abstracción del repositorio (no de Eloquent directamente),
 * cumpliendo el principio de Inversión de Dependencias (SOLID - D).
 */
class HotelService
{
    public function __construct(
        private readonly HotelRepositoryInterface $hotels
    ) {
    }

    public function list(): Collection
    {
        return $this->hotels->all();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->hotels->paginate($perPage);
    }

    public function find(int $id): ?Hotel
    {
        return $this->hotels->findById($id);
    }

    /**
     * Registra un nuevo hotel. La unicidad de name/nit se valida en el
     * Form Request y se respalda con índices únicos en la base de datos.
     */
    public function create(array $data): Hotel
    {
        return $this->hotels->create($data);
    }

    public function update(Hotel $hotel, array $data): Hotel
    {
        return $this->hotels->update($hotel, $data);
    }

    public function delete(Hotel $hotel): bool
    {
        return $this->hotels->delete($hotel);
    }
}
