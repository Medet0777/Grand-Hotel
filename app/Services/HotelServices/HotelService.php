<?php

namespace App\Services\HotelServices;

use App\Contracts\HotelContracts\HotelServiceContract;
use App\Facades\Repository;
use App\Http\DTO\Hotel\CreateHotelDTO;
use App\Http\DTO\Hotel\UpdateHotelDTO;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class HotelService implements HotelServiceContract
{
    public function getAllHotels(): Collection
    {
        return Repository::hotel()->all();
    }

    public function getPaginatedHotels(int $perPage = 15): LengthAwarePaginator
    {
        return Repository::hotel()->paginate($perPage);
    }

    public function getHotelById(int $id): ?Hotel
    {
        return Repository::hotel()->findById($id);
    }

    public function createNewHotel(CreateHotelDTO $dto): Hotel
    {
        return Repository::hotel()->create($dto);
    }

    public function updateHotelDetails(int $id, UpdateHotelDTO $dto): bool
    {
        return Repository::hotel()->update($id, $dto);
    }

    public function deleteHotel(int $id): bool
    {
        return Repository::hotel()->delete($id);
    }

    public function getPopularHotels(int $limit = 10): Collection
    {
        return Repository::hotel()->getPopular($limit);
    }

    public function searchHotelsByLocation(string $location): Collection
    {
        return Repository::hotel()->searchByLocation($location);
    }
}
