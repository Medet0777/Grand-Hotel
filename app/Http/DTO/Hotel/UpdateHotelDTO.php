<?php

namespace App\Http\DTO\Hotel;

use App\Http\Requests\Hotel\UpdateHotelRequest;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Attributes\CastWith;
use App\Casts\FloatCast;

class UpdateHotelDTO extends DataTransferObject
{
    public ?string $name;
    public ?string $location_name;
    #[CastWith(FloatCast::class)]
    public ?float $latitude;
    #[CastWith(FloatCast::class)]
    public ?float $longitude;
    public ?float $rating;
    public ?float $price_per_night;
    public ?string $description;


    public static function fromRequest(UpdateHotelRequest $request): self
    {
        $validatedData = $request->validated();

        return new self([
            'name' => $validatedData['name'] ?? null,
            'location_name' => $validatedData['location_name'] ?? null,
            'latitude' => $validatedData['latitude'] ?? null,
            'longitude' => $validatedData['longitude'] ?? null,
            'rating' => $validatedData['rating'] ?? null,
            'price_per_night' => $validatedData['price_per_night'] ?? null,
            'description' => $validatedData['description'] ?? null,
        ]);
    }
}
