<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHotelRoomRequest;
use App\Http\Resources\RoomAssignmentResource;
use App\Models\Hotel;
use App\Services\RoomAssignmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Gestiona la asignación de habitaciones a un hotel concreto.
 */
class HotelRoomController extends Controller
{
    public function __construct(
        private readonly RoomAssignmentService $service
    ) {
    }

    /**
     * POST /api/v1/hotels/{hotel}/rooms
     *
     * Las reglas de negocio se aplican en el servicio; si alguna falla,
     * BusinessRuleException devuelve automáticamente un 422.
     */
    public function store(StoreHotelRoomRequest $request, Hotel $hotel): JsonResponse
    {
        $assignment = $this->service->assign($hotel, $request->validated());

        return (new RoomAssignmentResource($assignment))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
