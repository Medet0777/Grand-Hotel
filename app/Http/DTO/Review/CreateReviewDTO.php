<?php

namespace App\Http\DTO\Review;

use Spatie\DataTransferObject\DataTransferObject;

class CreateReviewDTO extends DataTransferObject
{
    public int $user_id;
    public int $hotel_id;
    public int $rating;
    public string $description;

}
