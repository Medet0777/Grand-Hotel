<?php

namespace App\Contracts\BookingContracts;

use App\Http\DTO\Booking\CreateBookingDTO;
use App\Http\DTO\Booking\UpdateBookingDTO;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface BookingServiceContract
{
    public function getAllBookings(): Collection;

    public function getPaginatedBookings(int $perPage = 15): LengthAwarePaginator;

    public function getBookingById(int $id): ?Booking;

    public function createBooking(CreateBookingDTO $dto): Booking;

    public function updateBooking(int $id, UpdateBookingDTO $dto): Booking;

    public function deleteBooking(int $id): bool;
}
