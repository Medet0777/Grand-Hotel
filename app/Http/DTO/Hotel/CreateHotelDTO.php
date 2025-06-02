<?php

namespace App\Http\DTO\Hotel;

use App\Http\Requests\Hotel\CreateHotelRequest;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Attributes\CastWith;
use App\Casts\FloatCast;

class CreateHotelDTO extends DataTransferObject
{
    public string $name;
    public string $location_name;
    #[CastWith(FloatCast::class)]
    public float $latitude;
    #[CastWith(FloatCast::class)]
    public float $longitude;
    #[CastWith(FloatCast::class)]
    public ?float $rating;
    public float $price_per_night;
    public ?string $description;


    public static function fromRequest(CreateHotelRequest $request): self
    {
        $validatedData = $request->validated();

        return new self([
            'name' => $validatedData['name'],
            'location_name' => $validatedData['location_name'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
            'rating' => $validatedData['rating'] ?? null,
            'price_per_night' => $validatedData['price_per_night'],
            'description' => $validatedData['description'] ?? null,
        ]);
    }
}
