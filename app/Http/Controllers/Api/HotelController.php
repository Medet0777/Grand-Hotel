<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\HotelNotFoundException;
use App\Facades\Service;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hotel\CreateHotelRequest;
use App\Http\Requests\Hotel\FilterHotelRequest;
use App\Http\Requests\Hotel\SearchHotelRequest;
use App\Http\Requests\Hotel\UpdateHotelRequest;
use App\Http\Resources\HotelResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 * name="Hotels",
 * description="Операции, связанные с отелями"
 * )
 */
class HotelController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/hotels",
     *     tags={"Hotels"},
     *     summary="Список всех отелей",
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/HotelResource")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $hotels = Service::hotel()->getAll();
        return HotelResource::collection($hotels)->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/hotels",
     *     tags={"Hotels"},
     *     summary="Создать новый отель",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "location_name", "latitude", "longitude", "price_per_night"},
     *             @OA\Property(property="name", type="string", example="Rixos Almaty"),
     *             @OA\Property(property="location_name", type="string", example="Almaty"),
     *             @OA\Property(property="latitude", type="number", format="float", example=43.238949),
     *             @OA\Property(property="longitude", type="number", format="float", example=76.889709),
     *             @OA\Property(property="rating", type="number", format="float", example=4.5),
     *             @OA\Property(property="price_per_night", type="number", format="float", example=250.00),
     *             @OA\Property(property="description", type="string", example="Luxury hotel in the center."),
     *             @OA\Property(
     *                 property="facilities",
     *                 type="array",
     *                 @OA\Items(type="string", example="Free WiFi")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Отель успешно создан",
     *         @OA\JsonContent(ref="#/components/schemas/HotelResource")
     *     ),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function store(CreateHotelRequest $request): JsonResponse
    {
        $dto = $request->toDTO();
        $hotel = Service::hotel()->create($dto);
        return (new HotelResource($hotel))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/hotels/{id}",
     *     tags={"Hotels"},
     *     summary="Получить отель по ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Успешно", @OA\JsonContent(ref="#/components/schemas/HotelResource")),
     *     @OA\Response(response=404, description="Отель не найден")
     * )
     * @throws HotelNotFoundException
     */
    public function show(int $id): JsonResponse
    {
        $hotel = Service::hotel()->getById($id);
        if (!$hotel) {
            throw new HotelNotFoundException($id);
        }
        return (new HotelResource($hotel))->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/hotels/{id}",
     *     tags={"Hotels"},
     *     summary="Обновить отель",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Hotel"),
     *             @OA\Property(property="location_name", type="string", example="Astana"),
     *             @OA\Property(property="latitude", type="number", format="float", example=51.1605),
     *             @OA\Property(property="longitude", type="number", format="float", example=71.4704),
     *             @OA\Property(property="rating", type="number", format="float", example=4.8),
     *             @OA\Property(property="price_per_night", type="number", format="float", example=300.00),
     *             @OA\Property(property="description", type="string", example="Updated description"),
     *             @OA\Property(
     *                 property="facilities",
     *                 type="array",
     *                 @OA\Items(type="string", example="Spa")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Успешно", @OA\JsonContent(ref="#/components/schemas/HotelResource")),
     *     @OA\Response(response=404, description="Отель не найден"),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     * @throws HotelNotFoundException
     */
    public function update(UpdateHotelRequest $request, int $id): JsonResponse
    {
        $dto = $request->toDTO();
        if (Service::hotel()->update($id, $dto)) {
            $hotel = Service::hotel()->getById($id);
            return (new HotelResource($hotel))->response()->setStatusCode(Response::HTTP_OK);
        }
        throw new HotelNotFoundException($id);
    }

    /**
     * @OA\Delete(
     *     path="/api/hotels/{id}",
     *     tags={"Hotels"},
     *     summary="Удалить отель",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Удалено"),
     *     @OA\Response(response=404, description="Не найден")
     * )
     * @throws HotelNotFoundException
     */
    public function destroy(int $id): JsonResponse
    {
        if (Service::hotel()->delete($id)) {
            return response()->json(['message' => 'Hotel deleted successfully'], Response::HTTP_NO_CONTENT);
        }
        throw new HotelNotFoundException($id);
    }

    /**
     * @OA\Get(
     *     path="/api/hotels/popular",
     *     tags={"Hotels"},
     *     summary="Список популярных отелей",
     *     @OA\Response(
     *         response=200,
     *         description="Успешно",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/HotelResource"))
     *     )
     * )
     */
    public function getPopular(): JsonResponse
    {
        $hotels = Service::hotel()->getPopular();
        return response()->json($hotels, Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/hotels/random",
     *     tags={"Hotels"},
     *     summary="Случайный список отелей",
     *     @OA\Response(
     *         response=200,
     *         description="Успешно",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/HotelResource"))
     *     )
     * )
     */
    public function getRandom(): JsonResponse
    {
        $hotels = Service::hotel()->getRandom();
        return response()->json($hotels, Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/api/hotels/search",
     *     tags={"Hotels"},
     *     summary="Поиск по названию отеля или локации",
     *     @OA\Parameter(name="query", in="query", required=true, @OA\Schema(type="string")),
     *     @OA\Response(
     *         response=200,
     *         description="Результаты поиска",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/HotelResource"))
     *         )
     *     ),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function search(SearchHotelRequest $request): JsonResponse
    {
        $query = $request->validated()['query'];
        $hotels = Service::hotel()->search($query);

        return response()->json([
            'data' => HotelResource::collection($hotels),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/hotels/filter",
     *     tags={"Hotels"},
     *     summary="Фильтр по цене и рейтингу",
     *     @OA\Parameter(name="min_price", in="query", @OA\Schema(type="number", format="float")),
     *     @OA\Parameter(name="max_price", in="query", @OA\Schema(type="number", format="float")),
     *     @OA\Parameter(name="rating", in="query", @OA\Schema(type="number", format="float")),
     *     @OA\Response(
     *         response=200,
     *         description="Фильтр применён",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/HotelResource"))
     *         )
     *     )
     * )
     */
    public function filter(FilterHotelRequest $request): JsonResponse
    {
        $hotels = Service::hotel()->filter($request->toDTO());
        return response()->json([
            'data' => HotelResource::collection($hotels)
        ]);
    }
}
