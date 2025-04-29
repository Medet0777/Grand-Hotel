<?php
namespace App\Repositories;

use App\Contracts\HotelContracts\HotelRepositoryContract;
use App\Http\DTO\Hotel\CreateHotelDTO;
use App\Http\DTO\Hotel\UpdateHotelDTO;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class HotelRepository implements HotelRepositoryContract
{
    public function all(): Collection
    {
        return Hotel::all();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Hotel::paginate($perPage);
    }

    public function findById(int $id): ?Hotel
    {
        return Hotel::findOrFail($id);
    }

    public function create(CreateHotelDTO $dto): Hotel
    {
        return Hotel::create([
            'name' => $dto->name,
            'location' => $dto->location,
            'rating' => $dto->rating,
            'price_per_night' => $dto->price_per_night,
            'description' => $dto->description,
        ]);
    }

    public function update(int $id, UpdateHotelDTO $dto): bool
    {
        $hotel = Hotel::findOrFail($id);
        return $hotel->update([
            'name' => $dto->name,
            'location' => $dto->location,
            'rating' => $dto->rating,
            'price_per_night' => $dto->price_per_night,
            'description' => $dto->description,
        ]);
    }

    public function delete(int $id): bool
    {
        $hotel = Hotel::findOrFail($id);
        return $hotel->delete();
    }


    public function searchByLocation(string $location): Collection
    {
        return Hotel::where('location', 'like', '%' . $location . '%')->get();
    }


    public function getPopular(int $limit = 10): Collection
    {
        return Hotel::orderBy('rating', 'desc')->take($limit)->get();
    }

}
