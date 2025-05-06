<?php

namespace App\Contracts\BookingContracts;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\DTO\Booking\CreateBookingDTO;
use App\Http\DTO\Booking\UpdateBookingDTO;

interface BookingRepositoryContract
{
    public function all(): Collection;
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Booking;

    public function create(CreateBookingDTO $dto): Booking;

    public function update(int $id,UpdateBookingDTO $dto): Booking;

    public function delete(int $id):bool;
}
