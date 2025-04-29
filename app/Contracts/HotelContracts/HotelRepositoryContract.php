<?php

namespace App\Contracts\HotelContracts;

use App\Http\DTO\Hotel\CreateHotelDTO;
use App\Http\DTO\Hotel\UpdateHotelDTO;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface HotelRepositoryContract
{
    public function all(): Collection;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function findById(int $id): ?Hotel;
    public function create(CreateHotelDTO $dto): Hotel;
    public function update(int $id, UpdateHotelDTO $dto): bool;
    public function delete(int $id): bool;

}
