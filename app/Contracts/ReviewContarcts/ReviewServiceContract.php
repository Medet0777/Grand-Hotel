<?php

namespace App\Contracts\ReviewContarcts;

use App\Http\DTO\Review\CreateReviewDTO;
use Illuminate\Http\JsonResponse;

interface ReviewServiceContract
{
    public function createReview(CreateReviewDTO $dto): JsonResponse;

    public function getReviewsByHotelId(int $hotelId): JsonResponse;
}
