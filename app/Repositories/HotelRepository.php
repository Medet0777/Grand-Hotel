<?php

namespace App\Repositories;

use App\Contracts\HotelContracts\HotelRepositoryContract;
use App\Http\DTO\Hotel\CreateHotelDTO;
use App\Http\DTO\Hotel\FilterHotelDTO;
use App\Http\DTO\Hotel\UpdateHotelDTO;
use App\Models\Hotel;
use App\Models\Location;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class HotelRepository implements HotelRepositoryContract
{
    public function all(): Collection
    {
        return Hotel::with(['location', 'hotelFacilities'])->get();
    }

    public function paginate(int $perPage = 15, int $page = null): LengthAwarePaginator
    {
        $page = $page ?? LengthAwarePaginator::resolveCurrentPage();

        return Hotel::with(['location', 'hotelFacilities'])->paginate($perPage, ['*'], 'page', $page);
    }

    public function findById(int $id): ?Hotel
    {
        return Hotel::with(['location', 'hotelFacilities'])->find($id);
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

            foreach ($dto->facilities as $facilityName) {
                $hotel->facilities()->create([
                    'facility_name' => $facilityName
                ]);
            }

            DB::commit();
            return $hotel;
        } catch (Exception $e) {
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


            $locationData = array_filter([
                'name' => $dto->location_name,
                'latitude' => $dto->latitude,
                'longitude' => $dto->longitude,
            ], fn ($value) => !is_null($value));

            if (!empty($locationData)) {
                $location->update($locationData);
            }


            $hotelData = array_filter([
                'name' => $dto->name,
                'rating' => $dto->rating,
                'price_per_night' => $dto->price_per_night,
                'description' => $dto->description,
            ], fn ($value) => !is_null($value));

            if (!empty($hotelData)) {
                $hotel->update($hotelData);
            }


            if (!is_null($dto->facilities)) {
                $hotel->facilities()->delete();
                foreach ($dto->facilities as $facilityName) {
                    $hotel->facilities()->create([
                        'facility_name' => $facilityName
                    ]);
                }
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
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
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error deleting hotel ' . $id . ': ' . $e->getMessage());
            throw $e;
        }
    }

    public function getPopular(): Collection
    {
        return Hotel::with(['location', 'hotelFacilities'])
            ->orderByDesc('rating')
            ->limit(10)
            ->get();
    }

    public function getRandomly(): Collection
    {
        return Hotel::with(['location', 'hotelFacilities'])
            ->inRandomOrder()
            ->limit(10)
            ->get();
    }

    public function search(string $query): Collection
    {
        return Hotel::with(['location', 'hotelFacilities'])
            ->where('name', 'ILIKE', '%' . $query . '%')
            ->orWhereHas('location', function ($q) use ($query) {
                $q->where('name', 'ILIKE', '%' . $query . '%');
            })
            ->get();
    }

    public function filter(FilterHotelDTO $dto): Collection
    {
        return Hotel::with(['location', 'hotelFacilities'])
            ->when($dto->min_price, fn($q) => $q->where('price_per_night', '>=', $dto->min_price))
            ->when($dto->max_price, fn($q) => $q->where('price_per_night', '<=', $dto->max_price))
            ->when($dto->rating, fn($q) => $q->where('rating', '>=', $dto->rating))
            ->get();
    }

}
