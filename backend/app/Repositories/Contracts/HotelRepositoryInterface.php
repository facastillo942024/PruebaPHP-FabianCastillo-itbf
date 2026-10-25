<?php

namespace App\Repositories\Contracts;

use App\Models\Hotel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Contrato de persistencia para la entidad Hotel.
 *
 * Abstrae el acceso a datos de la capa de servicio (Dependency Inversion),
 * permitiendo sustituir la implementación sin tocar la lógica de negocio.
 */
interface HotelRepositoryInterface
{
    /**
     * @return Collection<int, Hotel>
     */
    public function all(): Collection;

    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Hotel;

    public function create(array $data): Hotel;

    public function update(Hotel $hotel, array $data): Hotel;

    public function delete(Hotel $hotel): bool;
}
