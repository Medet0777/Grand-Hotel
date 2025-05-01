<?php

namespace App\Http\Controllers\Api;


use App\Facades\Service;
use App\Http\Controllers\Controller;
use App\Http\Requests\Room\CreateRoomRequest;
use App\Http\Requests\Room\UpdateRoomRequest;
use App\Http\Resources\RoomResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
/**
 * @OA\Tag(
 *     name="Rooms",
 *     description="Операции, связанные с комнатами"
 * )
 */
class RoomController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/rooms",
     *     tags={"Rooms"},
     *     summary="Получить список комнат",
     *     description="Возвращает пагинированный список всех комнат.",
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="hotel_id", type="integer", example=3),
     *                 @OA\Property(property="room_type", type="string", example="Deluxe"),
     *                 @OA\Property(property="price_per_night", type="number", format="float", example=120.50),
     *                 @OA\Property(property="available", type="boolean", example=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $rooms = Service::room()->getPaginatedRooms();
        return RoomResource::collection($rooms)->response()->setStatusCode(Response::HTTP_OK);
    }
    /**
     * @OA\Post(
     *     path="/api/rooms",
     *     tags={"Rooms"},
     *     summary="Создать новую комнату",
     *     description="Создает новую комнату на основе переданных данных.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"hotel_id", "room_type", "price_per_night"},
     *             @OA\Property(property="hotel_id", type="integer", example=3),
     *             @OA\Property(property="room_type", type="string", example="Standard"),
     *             @OA\Property(property="price_per_night", type="number", format="float", example=100.00),
     *             @OA\Property(property="available", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Комната успешно создана",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="hotel_id", type="integer", example=3),
     *             @OA\Property(property="room_type", type="string", example="Standard"),
     *             @OA\Property(property="price_per_night", type="number", format="float", example=100.00),
     *             @OA\Property(property="available", type="boolean", example=true),
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
    public function store(CreateRoomRequest $request): JsonResponse
    {
        $dto = $request->toDTO();
        $room = Service::room()->createNewRoom($dto);
        return (new RoomResource($room))->response()->setStatusCode(Response::HTTP_CREATED);
    }
    /**
     * @OA\Get(
     *     path="/api/rooms/{id}",
     *     tags={"Rooms"},
     *     summary="Получить информацию о комнате по ID",
     *     description="Возвращает информацию о комнате с указанным ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID комнаты",
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="hotel_id", type="integer", example=3),
     *             @OA\Property(property="room_type", type="string", example="Deluxe"),
     *             @OA\Property(property="price_per_night", type="number", format="float", example=120.50),
     *             @OA\Property(property="available", type="boolean", example=true),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Комната не найдена"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $room = Service::room()->getRoomById($id);
        if (!$room) {

            return response()->json(['message' => 'Room not found'], Response::HTTP_NOT_FOUND);
        }
        return (new RoomResource($room))->response()->setStatusCode(Response::HTTP_OK);
    }
    /**
     * @OA\Put(
     *     path="/api/rooms/{id}",
     *     tags={"Rooms"},
     *     summary="Обновить информацию о комнате",
     *     description="Обновляет информацию о комнате с указанным ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID комнаты",
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="room_type", type="string", example="Suite"),
     *             @OA\Property(property="price_per_night", type="number", format="float", example=150.00),
     *             @OA\Property(property="available", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Комната успешно обновлена",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="hotel_id", type="integer", example=3),
     *             @OA\Property(property="room_type", type="string", example="Suite"),
     *             @OA\Property(property="price_per_night", type="number", format="float", example=150.00),
     *             @OA\Property(property="available", type="boolean", example=false),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Комната не найдена"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации"
     *     )
     * )
     */
    public function update(UpdateRoomRequest $request, int $id): JsonResponse
    {
        $dto = $request->toDTO();
        if (Service::room()->updateRoomDetails($id, $dto)) {
            $room = Service::room()->getRoomById($id);
            return (new RoomResource($room))->response()->setStatusCode(Response::HTTP_OK);
        }
        return response()->json(['message' => 'Room not found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Delete(
     *     path="/api/rooms/{id}",
     *     tags={"Rooms"},
     *     summary="Удалить комнату",
     *     description="Удаляет комнату с указанным ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID комнаты",
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Комната успешно удалена"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Комната не найдена"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        if (Service::room()->deleteRoom($id)) {
            return response()->json(['message' => 'Room deleted successfully'], Response::HTTP_NO_CONTENT);
        }
        return response()->json(['message' => 'Room not found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Get(
     *     path="/api/hotels/{hotelId}/rooms",
     *     tags={"Rooms"},
     *     summary="Получить комнаты по ID отеля",
     *     description="Возвращает список комнат для конкретного отеля по его ID.",
     *     @OA\Parameter(
     *         name="hotelId",
     *         in="path",
     *         required=true,
     *         description="ID отеля",
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешный запрос",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="hotel_id", type="integer", example=3),
     *                 @OA\Property(property="room_type", type="string", example="Deluxe"),
     *                 @OA\Property(property="price_per_night", type="number", format="float", example=120.50),
     *                 @OA\Property(property="available", type="boolean", example=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-30T05:52:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Отель не найден"
     *     )
     * )
     */
    public function getRoomsByHotel(int $hotelId): JsonResponse
    {
        $rooms = Service::room()->getRoomsByHotelId($hotelId);
        if (!$rooms) {
            return response()->json(['message' => 'Hotel not found'], Response::HTTP_NOT_FOUND);
        }
        return RoomResource::collection($rooms)->response()->setStatusCode(Response::HTTP_OK);
    }
}
