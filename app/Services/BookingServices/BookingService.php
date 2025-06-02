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

    public function getAll(): Collection
    {
        return Repository::booking()->all();
    }

    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
       return Repository::booking()->paginate($perPage);
    }

    public function getById(int $id): ?Booking
    {
       return Repository::booking()->findById($id);
    }

    public function create(CreateBookingDTO $dto): Booking
    {
        return Repository::booking()->create($dto);
    }

    public function update(int $id, UpdateBookingDTO $dto): Booking
    {
        return Repository::booking()->update($id, $dto);
    }

    public function delete(int $id): bool
    {
       return Repository::booking()->delete($id);
    }
}
