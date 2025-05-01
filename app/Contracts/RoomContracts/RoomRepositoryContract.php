<?php

namespace App\Contracts\RoomContracts;

use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface RoomRepositoryContract
{
    public function getPaginatedRooms(): LengthAwarePaginator;
    public function getAllRooms(): Collection;
    public function getRoomById(int $id): ?Room;
    public function createNewRoom(array $data): Room;
    public function updateRoomDetails(int $id, array $data): bool;
    public function deleteRoom(int $id): bool;
    public function getRoomsByHotelId(int $hotelId): Collection;
}
