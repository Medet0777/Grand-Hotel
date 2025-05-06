<?php

namespace App\Repositories;

use App\Contracts\BookingContracts\BookingRepositoryContract;
use App\Http\DTO\Booking\CreateBookingDTO;
use App\Http\DTO\Booking\UpdateBookingDTO;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BookingRepository implements BookingRepositoryContract
{

    public function all(): Collection
    {
        return Booking::select('id', 'user_id', 'hotel_id', 'room_id', 'check_in_date', 'check_out_date', 'status')->get();
    }

    public function paginate(int $perPage = 15, int $page = null): LengthAwarePaginator
    {
        $page = $page ?? LengthAwarePaginator::resolveCurrentPage();

        return Booking::select('id', 'user_id', 'hotel_id', 'room_id', 'check_in_date', 'check_out_date', 'status')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function findById(int $id): ?Booking
    {
        return Booking::select('id', 'user_id', 'hotel_id', 'room_id', 'check_in_date', 'check_out_date', 'status')->find($id);
    }

    public function create(CreateBookingDTO $dto): Booking
    {
        return Booking::create([
            'user_id' => $dto->user_id,
            'hotel_id' => $dto->hotel_id,
            'room_id' => $dto->room_id,
            'check_in_date' => $dto->check_in_date,
            'check_out_date' => $dto->check_out_date,
            'status' => $dto->status,
        ]);
    }

    public function update(int $id, UpdateBookingDTO $dto): Booking
    {
        $booking = Booking::findOrFail($id);
        $booking->update([
            'user_id' => $dto->user_id,
            'hotel_id' => $dto->hotel_id,
            'room_id' => $dto->room_id,
            'check_in_date' => $dto->check_in_date,
            'check_out_date' => $dto->check_out_date,
            'status' => $dto->status,
        ]);
        return $booking;
    }

    public function delete(int $id): bool
    {
        $booking = Booking::findOrFail($id);
        return $booking->delete();
    }
}
