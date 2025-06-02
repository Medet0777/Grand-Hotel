<?php

namespace App\Repositories;

use App\Contracts\ReviewContarcts\ReviewRepositoryContract;
use App\Http\DTO\Review\CreateReviewDTO;
use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;

class ReviewRepository implements ReviewRepositoryContract
{
    public function create(CreateReviewDTO $dto): Review
    {
        return Review::create($dto->toArray());
    }

    public function getByHotelId(int $hotelId): Collection
    {
        return Review::where('hotel_id', $hotelId)->with('user')->get();
    }
}
