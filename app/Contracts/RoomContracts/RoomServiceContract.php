<?php

namespace App\Contracts\RoomContracts;

use App\Http\DTO\Room\CreateRoomDTO;
use App\Http\DTO\Room\UpdateRoomDTO;
use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
interface RoomServiceContract
{
    public function getPaginatedRooms(): LengthAwarePaginator;
    public function getAllRooms(): Collection;
    public function getRoomById(int $id): ?Room;
    public function createNewRoom(CreateRoomDTO $dto): Room;
    public function updateRoomDetails(int $id, UpdateRoomDTO $dto): bool;
    public function deleteRoom(int $id): bool;
    public function getRoomsByHotelId(int $hotelId): Collection;
}
