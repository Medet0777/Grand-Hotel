<?php

namespace App\Contracts\HotelContracts;

use App\Http\DTO\Hotel\CreateHotelDTO;
use App\Http\DTO\Hotel\UpdateHotelDTO;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface HotelServiceContract
{
    public function getAll(): Collection;
    public function getPaginated(int $perPage = 15): LengthAwarePaginator;
    public function getById(int $id): ?Hotel;
    public function create(CreateHotelDTO $dto): Hotel;
    public function update(int $id, UpdateHotelDTO $dto): bool;
    public function delete(int $id): bool;
    public function getPopular(): Collection;
    public function getRandom(): Collection;

}
