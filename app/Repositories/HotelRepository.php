<?php

namespace App\Repositories;

use App\Contracts\HotelContracts\HotelRepositoryContract;
use App\Http\DTO\Hotel\CreateHotelDTO;
use App\Http\DTO\Hotel\UpdateHotelDTO;
use App\Models\Hotel;
use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class HotelRepository implements HotelRepositoryContract
{
    public function all(): Collection
    {
        return Hotel::all();
    }

    public function paginate(int $perPage = 15,int $page = null): LengthAwarePaginator
    {
        $page = $page ?? LengthAwarePaginator::resolveCurrentPage();

        return Hotel::paginate($perPage, ['*'], 'page', $page);
    }

    public function findById(int $id): ?Hotel
    {
        return Hotel::find($id);
    }

    public function create(CreateHotelDTO $dto): Hotel
    {
        try {
            DB::beginTransaction();


            $location = Location::create([
                'name' => $dto->location_name,
                'latitude' => $dto->latitude,
                'longitude' => $dto->longitude,
            ]);

            $hotel = Hotel::create([
                'name' => $dto->name,
                'location_id' => $location->id,
                'rating' => $dto->rating,
                'price_per_night' => $dto->price_per_night,
                'description' => $dto->description,
            ]);

            DB::commit();
            return $hotel;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating hotel and location: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(int $id, UpdateHotelDTO $dto): bool
    {
        try {
            DB::beginTransaction();

            $hotel = Hotel::findOrFail($id);
            $location = Location::findOrFail($hotel->location_id);

            // Update the Location
            $location->update([
                'name' => $dto->location_name,
                'latitude' => $dto->latitude,
                'longitude' => $dto->longitude,
            ]);

            $hotel->update([
                'name' => $dto->name,
                'rating' => $dto->rating,
                'price_per_night' => $dto->price_per_night,
                'description' => $dto->description,
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating hotel: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        try {
            DB::beginTransaction();

            $hotel = Hotel::findOrFail($id);
            $locationId = $hotel->location_id;

            $hotel->delete();
            Location::destroy($locationId);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting hotel ' . $id . ': ' . $e->getMessage());
            throw $e;
        }
    }

    public function getPopular(): Collection
    {
        return Hotel::orderByDesc('rating')
            ->limit(10)
            ->get();
    }

    public function getRandomly(): Collection
    {
        return Hotel::inRandomOrder()
            ->limit(10)
            ->get();
    }
}
