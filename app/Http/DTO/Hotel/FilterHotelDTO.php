<?php

namespace App\Http\DTO\Hotel;

use App\Http\Requests\Hotel\FilterHotelRequest;
use Spatie\DataTransferObject\DataTransferObject;

class FilterHotelDTO extends DataTransferObject
{
        public ?float $min_price = null;
        public ?float $max_price = null;
        public ?int $rating = null;

        public static function fromRequest(FilterHotelRequest $request):self
        {
            $validatedData = $request->validated();

            return new self([
                'min_price' => $validatedData['min_price'] ?? null,
                'max_price' => $validatedData['max_price'] ?? null,
                'rating' => $validatedData['rating'] ?? null,
            ]);
        }
}
