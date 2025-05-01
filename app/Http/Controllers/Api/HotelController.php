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

/**
 * @OA\Tag(
 *     name="Hotels",
 *     description="Операции, связанные с отелями"
 * )
 */
class HotelController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/hotels",
     *     tags={"Hotels"},
     *     summary="Получить список отелей",
     *     description="Возвращает пагинированный список всех отелей.",
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Schema(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=10),
     *                     @OA\Property(property="name", type="string", example="Luxury Inn"),
     *                     @OA\Property(property="location", type="string", example="Almaty"),
     *                     @OA\Property(property="rating", type="number", format="float", example=4.5),
     *                     @OA\Property(property="price_per_night", type="number", format="float", example=150.00),
     *                     @OA\Property(property="description", type="string", example="A comfortable hotel in the city center."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $hotels = Service::hotel()->getPaginatedHotels();
        return HotelResource::collection($hotels)->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/hotels",
     *     tags={"Hotels"},
     *     summary="Создать новый отель",
     *     description="Создает новый отель на основе переданных данных.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "location", "price_per_night", "description"},
     *             @OA\Property(property="name", type="string", example="Luxury Inn"),
     *             @OA\Property(property="location", type="string", example="Almaty"),
     *             @OA\Property(property="price_per_night", type="number", format="float", example=150.00),
     *             @OA\Property(property="description", type="string", example="A comfortable hotel in the city center.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Отель успешно создан",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=10),
     *             @OA\Property(property="name", type="string", example="Luxury Inn"),
     *             @OA\Property(property="location", type="string", example="Almaty"),
     *             @OA\Property(property="rating", type="number", format="float", example=0),
     *             @OA\Property(property="price_per_night", type="number", format="float", example=150.00),
     *             @OA\Property(property="description", type="string", example="A comfortable hotel in the city center."),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации"
     *     )
     * )
     */
    public function store(CreateHotelRequest $request): JsonResponse
    {
        $dto = $request->toDTO();
        $hotel = Service::hotel()->createNewHotel($dto);
        return (new HotelResource($hotel))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/hotels/{id}",
     *     tags={"Hotels"},
     *     summary="Получить информацию об отеле по ID",
     *     description="Возвращает информацию об отеле с указанным ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID отеля",
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Grand Hotel"),
     *             @OA\Property(property="location", type="string", example="Astana"),
     *             @OA\Property(property="rating", type="number", format="float", example=4.8),
     *             @OA\Property(property="price_per_night", type="number", format="float", example=200.00),
     *             @OA\Property(property="description", type="string", example="A luxurious hotel in the capital."),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-29T10:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-29T10:00:00.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Отель не найден"
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/hotels/{id}",
     *     tags={"Hotels"},
     *     summary="Обновить информацию об отеле",
     *     description="Обновляет информацию об отеле с указанным ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID отеля",
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Grand Hotel Updated"),
     *             @OA\Property(property="rating", type="number", format="float", example=4.9),
     *             @OA\Property(property="price_per_night", type="number", format="float", example=220.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Отель успешно обновлен",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Grand Hotel Updated"),
     *             @OA\Property(property="location", type="string", example="Astana"),
     *             @OA\Property(property="rating", type="number", format="float", example=4.9),
     *             @OA\Property(property="price_per_night", type="number", format="float", example=220.00),
     *             @OA\Property(property="description", type="string", example="A luxurious hotel in the capital."),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-29T10:00:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Отель не найден"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации"
     *     )
     * )
     */
    public function update(UpdateHotelRequest $request, int $id): JsonResponse
    {
        $dto = $request->toDTO();
        if (Service::hotel()->updateHotelDetails($id, $dto)) {
            $hotel = Service::hotel()->getHotelById($id);
            return (new HotelResource($hotel))->response()->setStatusCode(Response::HTTP_OK);
        }
        return response()->json(['message' => 'Hotel not found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Delete(
     *     path="/api/hotels/{id}",
     *     tags={"Hotels"},
     *     summary="Удалить отель",
     *     description="Удаляет отель с указанным ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID отеля",
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Отель успешно удален"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Отель не найден"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        if (Service::hotel()->deleteHotel($id)) {
            return response()->json(['message' => 'Hotel deleted successfully'], Response::HTTP_NO_CONTENT);
        }
        return response()->json(['message' => 'Hotel not found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Get(
     *     path="/api/hotels/popular",
     *     tags={"Hotels"},
     *     summary="Получить список популярных отелей",
     *     description="Возвращает список наиболее популярных отелей.",
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=3),
     *                 @OA\Property(property="name", type="string", example="Best Western"),
     *                 @OA\Property(property="location", type="string", example="Karaganda"),
     *                 @OA\Property(property="rating", type="number", format="float", example=4.5),
     *                 @OA\Property(property="price_per_night", type="number", format="float", example=90.00),
     *                 @OA\Property(property="description", type="string", example="A popular choice for travelers."),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-28T12:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-28T12:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Некорректный запрос"
     *     )
     * )
     */
    public function popular(): JsonResponse
    {
        $popularHotels = Service::hotel()->getPopularHotels();
        return HotelResource::collection($popularHotels)->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/hotels/search",
     *     tags={"Hotels"},
     *     summary="Поиск отелей по местоположению",
     *     description="Возвращает список отелей, найденных по указанному местоположению.",
     *     @OA\Parameter(
     *         name="location",
     *         in="query",
     *         required=true,
     *         description="Местоположение для поиска",
     *         @OA\Schema(type="string", example="Almaty")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="Kazakhstan Hotel"),
     *                 @OA\Property(property="location", type="string", example="Almaty"),
     *                 @OA\Property(property="rating", type="number", format="float", example=4.2),
     *                 @OA\Property(property="price_per_night", type="number", format="float", example=120.00),
     *                 @OA\Property(property="description", type="string", example="A historic hotel in Almaty."),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-29T08:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-29T08:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Некорректный запрос"
     *     )
     * )
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
}
