<?php

namespace App\Providers;

use App\Repositories\Contracts\HotelRepositoryInterface;
use App\Repositories\Contracts\RoomAssignmentRepositoryInterface;
use App\Repositories\Eloquent\HotelRepository;
use App\Repositories\Eloquent\RoomAssignmentRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Vincula los contratos de repositorio con sus implementaciones Eloquent.
 *
 * Se usa la propiedad pública $bindings que Laravel registra automáticamente
 * en el contenedor. Así los servicios dependen de interfaces y el contenedor
 * resuelve la implementación concreta (Inversión de Dependencias). Cambiar de
 * ORM solo requeriría una nueva implementación referenciada aquí.
 *
 * @var array<class-string, class-string>
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public array $bindings = [
        HotelRepositoryInterface::class          => HotelRepository::class,
        RoomAssignmentRepositoryInterface::class => RoomAssignmentRepository::class,
    ];
}
