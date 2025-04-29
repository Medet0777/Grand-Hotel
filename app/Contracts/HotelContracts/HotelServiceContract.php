<?php

namespace App\Contracts\HotelContracts;

use App\Http\DTO\Hotel\CreateHotelDTO;
use App\Http\DTO\Hotel\UpdateHotelDTO;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface HotelServiceContract
{
    public function getAllHotels(): Collection;
    public function getPaginatedHotels(int $perPage = 15): LengthAwarePaginator;
    public function getHotelById(int $id): ?Hotel;
    public function createNewHotel(CreateHotelDTO $dto): Hotel;
    public function updateHotelDetails(int $id, UpdateHotelDTO $dto): bool;
    public function deleteHotel(int $id): bool;

}
