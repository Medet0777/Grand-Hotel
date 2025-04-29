<?php
namespace App\Http\DTO\Hotel;

use App\Http\Requests\Hotel\UpdateHotelRequest;

class UpdateHotelDTO
{
    public ?string $name;
    public ?string $location;
    public ?float $rating;
    public ?float $price_per_night;
    public ?string $description;

    public function __construct(
        ?string $name = null,
        ?string $location = null,
        ?float $rating = null,
        ?float $price_per_night = null,
        ?string $description = null
    ) {
        $this->name = $name;
        $this->location = $location;
        $this->rating = $rating;
        $this->price_per_night = $price_per_night;
        $this->description = $description;
    }

    public static function fromRequest(UpdateHotelRequest $request): self
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
