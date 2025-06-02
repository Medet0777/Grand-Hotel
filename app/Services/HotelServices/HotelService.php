<?php

namespace App\Services\HotelServices;

use App\Contracts\HotelContracts\HotelServiceContract;
use App\Facades\Repository;
use App\Http\DTO\Hotel\CreateHotelDTO;
use App\Http\DTO\Hotel\FilterHotelDTO;
use App\Http\DTO\Hotel\UpdateHotelDTO;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class HotelService implements HotelServiceContract
{
    public function getAll(): Collection
    {
        return Repository::hotel()->all();
    }

    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Repository::hotel()->paginate($perPage);
    }

    public function getById(int $id): ?Hotel
    {
        return Repository::hotel()->findById($id);
    }

    public function create(CreateHotelDTO $dto): Hotel
    {
        return Repository::hotel()->create($dto);
    }

    public function update(int $id, UpdateHotelDTO $dto): bool
    {
        return Repository::hotel()->update($id, $dto);
    }

    public function delete(int $id): bool
    {
        return Repository::hotel()->delete($id);
    }

    public function getPopular(): Collection
    {
        return Repository::hotel()->getPopular();
    }

    public function getRandom(): Collection
    {
        return Repository::hotel()->getRandomly();
    }

    public function search(string $query): Collection
    {
        return Repository::hotel()->search($query);
    }

    public function filter(FilterHotelDTO $dto): Collection
    {
        return Repository::hotel()->filter($dto);
    }
}
