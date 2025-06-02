<?php

namespace App\Contracts\ReviewContarcts;

use App\Http\DTO\Review\CreateReviewDTO;
use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;

interface ReviewRepositoryContract
{
    public function create(CreateReviewDTO $dto): Review;
    public function getByHotelId(int $hotelId): Collection;
}
