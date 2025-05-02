<?php

namespace App\Services\BookingServices;

use App\Contracts\BookingContracts\BookingServiceContract;

use App\Facades\Repository;
use App\Http\DTO\Booking\CreateBookingDTO;
use App\Http\DTO\Booking\UpdateBookingDTO;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BookingService implements BookingServiceContract
{

    public function getAllBookings(): Collection
    {
        return Repository::booking()->all();
    }

    public function getPaginatedBookings(int $perPage = 15): LengthAwarePaginator
    {
       return Repository::booking()->paginate($perPage);
    }

    public function getBookingById(int $id): ?Booking
    {
       return Repository::booking()->findById($id);
    }

    public function createBooking(CreateBookingDTO $dto): Booking
    {
        return Repository::booking()->create($dto);
    }

    public function updateBooking(int $id, UpdateBookingDTO $dto): Booking
    {
        return Repository::booking()->update($id, $dto);
    }

    public function deleteBooking(int $id): bool
    {
       return Repository::booking()->delete($id);
    }
}
