<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="HotelResource",
 *     type="object",
 *     title="Hotel Resource",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Ritz Carlton"),
 *     @OA\Property(property="location", type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Almaty"),
 *         @OA\Property(property="latitude", type="number", format="float", example=43.2567),
 *         @OA\Property(property="longitude", type="number", format="float", example=76.9286)
 *     ),
 *     @OA\Property(property="rating", type="number", format="float", example=4.7),
 *     @OA\Property(property="price_per_night", type="number", format="float", example=180.00),
 *     @OA\Property(property="description", type="string", example="A luxury hotel with mountain views."),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-30T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-30T11:00:00Z")
 * )
 */
class HotelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => [
                'id' => $this->location?->id,
                'name' => $this->location?->name,
                'latitude' => $this->location?->latitude,
                'longitude' => $this->location?->longitude,
            ],
            'rating' => $this->rating,
            'price_per_night' => (float) $this->price_per_night,
            'description' => $this->description,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
