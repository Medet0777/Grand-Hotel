<?php

namespace App\Contracts\BookingContracts;

use App\Http\DTO\Booking\CreateBookingDTO;
use App\Http\DTO\Booking\UpdateBookingDTO;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BookingServiceContract
{
    public function getAll(): Collection;

    public function getPaginated(int $perPage = 15): LengthAwarePaginator;

    public function getById(int $id): ?Booking;

    public function create(CreateBookingDTO $dto): Booking;

    public function update(int $id, UpdateBookingDTO $dto): Booking;

    public function delete(int $id): bool;
}
