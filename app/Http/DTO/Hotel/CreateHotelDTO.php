<?php

namespace App\Http\DTO\Hotel;

use App\Http\Requests\Hotel\CreateHotelRequest;

class CreateHotelDTO
{
    public string $name;
    public string $location;
    public ?float $rating;
    public float $price_per_night;
    public ?string $description;

    public function __construct(string $name, string $location, ?float $rating, float $price_per_night, ?string $description)
    {
        $this->name = $name;
        $this->location = $location;
        $this->rating = $rating;
        $this->price_per_night = $price_per_night;
        $this->description = $description;
    }

    public static function fromRequest(CreateHotelRequest $request): self
    {
        return new self(
            $request->input('name'),
            $request->input('location'),
            $request->input('rating'),
            $request->input('price_per_night'),
            $request->input('description')
        );
    }
}
