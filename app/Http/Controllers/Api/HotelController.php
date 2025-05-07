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
     * path="/api/hotels",
     * tags={"Hotels"},
     * summary="Получить список отелей",
     * description="Возвращает пагинированный список всех отелей.",
     * @OA\Response(
     * response=200,
     * description="Успешный запрос",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Schema(
     * type="object",
     * @OA\Property(property="id", type="integer", example=10),
     * @OA\Property(property="name", type="string", example="Luxury Inn"),
     * @OA\Property(property="location", type="string", example="Almaty"),
     * @OA\Property(property="rating", type="number", format="float", example=4.5),
     * @OA\Property(property="price_per_night", type="number", format="float", example=150.00),
     * @OA\Property(property="description", type="string", example="A comfortable hotel in the city center."),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z"),
     * @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z")
     * )
     * )
     * )
     * )
     * )
     */
    public function index(): JsonResponse
    {
        $hotels = Service::hotel()->getAll();
        return HotelResource::collection($hotels)->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     * path="/api/hotels",
     * tags={"Hotels"},
     * summary="Создать новый отель",
     * description="Создает новый отель на основе переданных данных.",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * type="object",
     * required={"name", "location_name", "price_per_night", "latitude", "longitude"},
     * @OA\Property(property="name", type="string", example="Luxury Inn"),
     * @OA\Property(property="location_name", type="string", example="Almaty"),
     * @OA\Property(property="latitude", type="number", format="float", example=43.2567),
     * @OA\Property(property="longitude", type="number", format="float", example=76.9286),
     * @OA\Property(property="price_per_night", type="number", format="float", example=150.00),
     * @OA\Property(property="description", type="string", example="A comfortable hotel in the city center.")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Отель успешно создан",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="id", type="integer", example=10),
     * @OA\Property(property="name", type="string", example="Luxury Inn"),
     * @OA\Property(property="location", type="string", example="Almaty"),
     * @OA\Property(property="rating", type="number", format="float", example=0),
     * @OA\Property(property="price_per_night", type="number", format="float", example=150.00),
     * @OA\Property(property="description", type="string", example="A comfortable hotel in the city center."),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z"),
     * @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Ошибка валидации"
     * )
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
     * path="/api/hotels/{id}",
     * tags={"Hotels"},
     * summary="Получить информацию об отеле по ID",
     * description="Возвращает информацию об отеле с указанным ID.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID отеля",
     * @OA\Schema(type="integer", format="int64", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Успешный запрос",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="name", type="string", example="Grand Hotel"),
     * @OA\Property(property="location", type="string", example="Astana"),
     * @OA\Property(property="rating", type="number", format="float", example=4.8),
     * @OA\Property(property="price_per_night", type="number", format="float", example=200.00),
     * @OA\Property(property="description", type="string", example="A luxurious hotel in the capital."),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-29T10:00:00.000000Z"),
     * @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-29T10:00:00.000000Z")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Отель не найден"
     * )
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
     * path="/api/hotels/{id}",
     * tags={"Hotels"},
     * summary="Обновить информацию об отеле",
     * description="Обновляет информацию об отеле с указанным ID.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID отеля",
     * @OA\Schema(type="integer", format="int64", example=1)
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="name", type="string", example="Grand Hotel Updated"),
     * @OA\Property(property="location_name", type="string", example="Astana"),
     * @OA\Property(property="latitude", type="number", format="float", example=43.2567),
     * @OA\Property(property="longitude", type="number", format="float", example=76.9286),
     * @OA\Property(property="rating", type="number", format="float", example=4.9),
     * @OA\Property(property="price_per_night", type="number", format="float", example=220.00)
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Отель успешно обновлен",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="name", type="string", example="Grand Hotel Updated"),
     * @OA\Property(property="location", type="string", example="Astana"),
     * @OA\Property(property="rating", type="number", format="float", example=4.9),
     * @OA\Property(property="price_per_night", type="number", format="float", example=220.00),
     * @OA\Property(property="description", type="string", example="A luxurious hotel in the capital."),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-29T10:00:00.000000Z"),
     * @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Отель не найден"
     * ),
     * @OA\Response(
     * response=422,
     * description="Ошибка валидации"
     * )
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
     * path="/api/hotels/{id}",
     * tags={"Hotels"},
     * summary="Удалить отель",
     * description="Удаляет отель с указанным ID.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID отеля",
     * @OA\Schema(type="integer", format="int64", example=1)
     * ),
     * @OA\Response(
     * response=204,
     * description="Отель успешно удален"
     * ),
     * @OA\Response(
     * response=404,
     * description="Отель не найден"
     * )
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
     * path="/api/hotels/popular",
     * tags={"Hotels"},
     * summary="Получить список популярных отелей",
     * description="Возвращает список наиболее популярных отелей.",
     * @OA\Response(
     * response=200,
     * description="Успешный запрос",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Schema(
     * type="object",
     * @OA\Property(property="id", type="integer", example=3),
     * @OA\Property(property="name", type="string", example="Best Western"),
     * @OA\Property(property="location", type="string", example="Karaganda"),
     * @OA\Property(property="location_name", type="string", example="Karaganda"),
     * @OA\Property(property="rating", type="number", format="float", example=4.5),
     * @OA\Property(property="latitude", type="number", format="float", example="43.2567"),
     * @OA\Property(property="longitude", type="number", format="float", example="76.9286"),
     * @OA\Property(property="price_per_night", type="number", format="float", example=90.00),
     * @OA\Property(property="description", type="string", example="A popular choice for travelers."),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-28T12:00:00.000000Z"),
     * @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-28T12:00:00.000000Z")
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=400,
     * description="Некорректный запрос"
     * )
     * )
     */
    public function getPopular(): JsonResponse
    {
        $hotels = Service::hotel()->getPopular();
        return response()->json($hotels, Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     * path="/api/hotels/random",
     * tags={"Hotels"},
     * summary="Получить список случайных отелей",
     * description="Возвращает список случайно выбранных отелей.",
     * @OA\Response(
     * response=200,
     * description="Успешный запрос",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Schema(
     * type="object",
     * @OA\Property(property="id", type="integer", example=3),
     * @OA\Property(property="name", type="string", example="Best Western"),
     * @OA\Property(property="location", type="string", example="Karaganda"),
     * @OA\Property(property="location_name", type="string", example="Karaganda"),
     * @OA\Property(property="rating", type="number", format="float", example=4.5),
     * @OA\Property(property="latitude", type="number", format="float", example="43.2567"),
     * @OA\Property(property="longitude", type="number", format="float", example="76.9286"),
     * @OA\Property(property="price_per_night", type="number", format="float", example=90.00),
     * @OA\Property(property="description", type="string", example="A popular choice for travelers."),
     * @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-28T12:00:00.000000Z"),
     * @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-28T12:00:00.000000Z")
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=400,
     * description="Некорректный запрос"
     * )
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
     *     summary="Поиск отелей по названию или локации",
     *     description="Возвращает список отелей, соответствующих поисковому запросу. Ищет по названию отеля и названию локации.",
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         required=true,
     *         description="Поисковый запрос (название отеля или локации)",
     *         @OA\Schema(
     *             type="string",
     *             example="Ritz"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список найденных отелей",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/HotelResource")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации параметра запроса"
     *     )
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
     *     summary="Фильтрация отелей по цене и рейтингу",
     *     description="Возвращает список отелей, отфильтрованных по минимальной/максимальной цене и рейтингу.",
     *     @OA\Parameter(
     *         name="min_price",
     *         in="query",
     *         description="Минимальная цена за ночь",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=50.00)
     *     ),
     *     @OA\Parameter(
     *         name="max_price",
     *         in="query",
     *         description="Максимальная цена за ночь",
     *         required=false,
     *         @OA\Schema(type="number", format="float", example=200.00)
     *     ),
     *     @OA\Parameter(
     *         name="rating",
     *         in="query",
     *         description="Минимальный рейтинг отеля (1-5)",
     *         required=false,
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список отфильтрованных отелей",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/HotelResource")
     *             )
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
