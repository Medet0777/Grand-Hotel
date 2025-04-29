<?php

namespace App\Services\HotelServices;

use App\Contracts\HotelContracts\HotelRepositoryContract;
use App\Contracts\HotelContracts\HotelServiceContract;
use App\Http\DTO\Hotel\CreateHotelDTO;
use App\Http\DTO\Hotel\UpdateHotelDTO;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class HotelService implements HotelServiceContract
{
    protected HotelRepositoryContract $hotelRepository;

    public function __construct(HotelRepositoryContract $hotelRepository)
    {
        $this->hotelRepository = $hotelRepository;
    }

    public function getAllHotels(): Collection
    {
        return $this->hotelRepository->all();
    }

    public function getPaginatedHotels(int $perPage = 15): LengthAwarePaginator
    {
        return $this->hotelRepository->paginate($perPage);
    }

    public function getHotelById(int $id): ?Hotel
    {
        return $this->hotelRepository->findById($id);
    }

    public function createNewHotel(CreateHotelDTO $dto): Hotel
    {
        return $this->hotelRepository->create($dto);
    }

    public function updateHotelDetails(int $id, UpdateHotelDTO $dto): bool
    {
        return $this->hotelRepository->update($id, $dto);
    }

    public function deleteHotel(int $id): bool
    {
        return $this->hotelRepository->delete($id);
    }

    public function getPopularHotels(int $limit = 10): Collection
    {
        return $this->hotelRepository->getPopular($limit);
    }

    public function searchHotelsByLocation(string $location): Collection
    {
        return $this->hotelRepository->searchByLocation($location);
    }

}
