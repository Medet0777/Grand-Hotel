<?php

namespace App\Http\Controllers\Api;

use App\Facades\Service;
use App\Http\Controllers\Controller;
use App\Http\Requests\Review\CreateReviewRequest;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/reviews",
     * summary="Создать отзыв",
     * description="Создает новый отзыв для указанного отеля. Требуется аутентификация пользователя.",
     * operationId="createReview",
     * tags={"Reviews"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"user_id", "hotel_id", "rating", "description"},
     * @OA\Property(property="user_id", type="integer", example=1, description="ID пользователя, оставляющего отзыв"),
     * @OA\Property(property="hotel_id", type="integer", example=10, description="ID отеля, о котором оставляют отзыв"),
     * @OA\Property(property="rating", type="number", format="float", example=4.5, description="Рейтинг отеля (от 1 до 5 с шагом 0.5)"),
     * @OA\Property(property="description", type="string", example="Отличный отель, отличный сервис!", description="Текст отзыва")
     * )
     * ),
     * @OA\Response(
     * response="201",
     * description="Успешно создано",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Review created successfully")
     * )
     * ),
     * @OA\Response(
     * response="400",
     * description="Некорректный запрос",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The given data was invalid.")
     * )
     * ),
     * @OA\Response(
     * response="401",
     * description="Не аутентифицирован",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthenticated.")
     * )
     * ),
     * @OA\Response(
     * response="500",
     * description="Ошибка сервера",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Failed to create review: Internal server error.")
     * )
     * )
     * )
     */
    public function createReview(CreateReviewRequest $request):JsonResponse
    {
        return Service::review()->createReview($request->toDTO());
    }

    /**
     * @OA\Get(
     * path="/api/hotels/{hotelId}/reviews",
     * summary="Получить отзывы об отеле",
     * description="Возвращает все отзывы для указанного отеля.",
     * operationId="getReviewsByHotel",
     * tags={"Reviews"},
     * @OA\Parameter(
     * name="hotelId",
     * in="path",
     * description="ID отеля, для которого нужно получить отзывы",
     * required=true,
     * @OA\Schema(type="integer", format="int64", example=10)
     * ),
     * @OA\Response(
     * response="200",
     * description="Успешный ответ",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="user_id", type="integer", example=1),
     * @OA\Property(property="hotel_id", type="integer", example=10),
     * @OA\Property(property="rating", type="number", format="float", example=4.5),
     * @OA\Property(property="description", type="string", example="Отличный отель, отличный сервис!"),
     * @OA\Property(property="created_at", type="string", format="date-time"),
     * @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     * )
     * ),
     * @OA\Response(
     * response="404",
     * description="Отель не найден",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Hotel not found")
     * )
     * ),
     * @OA\Response(
     * response="500",
     * description="Ошибка сервера",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Internal server error.")
     * )
     * )
     * )
     */
    public function getReviewByHotel(int $hotelId): JsonResponse
    {
        return Service::review()->getReviewsByHotelId($hotelId);
    }
}
