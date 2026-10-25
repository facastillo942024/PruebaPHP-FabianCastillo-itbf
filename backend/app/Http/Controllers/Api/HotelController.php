<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Http\Resources\HotelResource;
use App\Models\Hotel;
use App\Services\HotelService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * CRUD RESTful de hoteles.
 *
 * El controlador es delgado: delega la lógica al HotelService y usa
 * Resources para la presentación (Single Responsibility).
 */
class HotelController extends Controller
{
    public function __construct(
        private readonly HotelService $service
    ) {
    }

    /** GET /api/v1/hotels */
    public function index(): JsonResponse
    {
        return HotelResource::collection($this->service->list())->response();
    }

    /** POST /api/v1/hotels */
    public function store(StoreHotelRequest $request): JsonResponse
    {
        $hotel = $this->service->create($request->validated());

        return (new HotelResource($hotel))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /** GET /api/v1/hotels/{hotel} */
    public function show(Hotel $hotel): JsonResponse
    {
        $hotel->load(['city', 'rooms.roomType', 'rooms.accommodation']);

        return (new HotelResource($hotel))->response();
    }

    /** PUT/PATCH /api/v1/hotels/{hotel} */
    public function update(UpdateHotelRequest $request, Hotel $hotel): JsonResponse
    {
        $hotel = $this->service->update($hotel, $request->validated());

        return (new HotelResource($hotel))->response();
    }

    /** DELETE /api/v1/hotels/{hotel} */
    public function destroy(Hotel $hotel): JsonResponse
    {
        $this->service->delete($hotel);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
