<?php
namespace App\Http\Controllers\Api;

use App\Facades\Service;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hotel\CreateHotelRequest;
use App\Http\Requests\Hotel\UpdateHotelRequest;
use App\Http\Resources\HotelResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HotelController extends Controller
{
    /**
     * Display a listing of the hotels.
     */
    public function index(): JsonResponse
    {
        $hotels = Service::hotel()->getPaginatedHotels();
        return HotelResource::collection($hotels)->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created hotel in storage.
     */
    public function store(CreateHotelRequest $request): JsonResponse
    {
        $dto = $request->toDTO();
        $hotel = Service::hotel()->createNewHotel($dto);
        return (new HotelResource($hotel))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified hotel.
     */
    public function show(int $id): JsonResponse
    {
        $hotel = Service::hotel()->getHotelById($id);
        if (!$hotel) {
            return response()->json(['message' => 'Hotel not found'], Response::HTTP_NOT_FOUND);
        }
        return (new HotelResource($hotel))->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified hotel in storage.
     */
    public function update(UpdateHotelRequest $request, int $id): JsonResponse
    {
        $dto = $request->toDTO(); // Предполагаем, что вы добавите toDTO() в ваш Request
        if (Service::hotel()->updateHotelDetails($id, $dto)) {
            $hotel = Service::hotel()->getHotelById($id);
            return (new HotelResource($hotel))->response()->setStatusCode(Response::HTTP_OK);
        }
        return response()->json(['message' => 'Hotel not found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * Remove the specified hotel from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        if (Service::hotel()->deleteHotel($id)) {
            return response()->json(['message' => 'Hotel deleted successfully'], Response::HTTP_NO_CONTENT);
        }
        return response()->json(['message' => 'Hotel not found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * Get popular hotels.
     */
    public function popular(): JsonResponse
    {
        $popularHotels = Service::hotel()->getPopularHotels();
        return HotelResource::collection($popularHotels)->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Search hotels by location.
     */
    public function search(Request $request): JsonResponse
    {
        $location = $request->query('location');
        if (!$location) {
            return response()->json(['message' => 'Location parameter is required'], Response::HTTP_BAD_REQUEST);
        }
        $hotels = Service::hotel()->searchHotelsByLocation($location);
        return HotelResource::collection($hotels)->response()->setStatusCode(Response::HTTP_OK);
    }

    // Добавьте другие методы контроллера на основе потребностей вашего приложения
    // (например, получение рекомендованных отелей, фильтрация по удобствам и т.д.)
}
