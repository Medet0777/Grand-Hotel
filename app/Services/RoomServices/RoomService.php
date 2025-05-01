<?php

namespace App\Services\RoomServices;

use App\Facades\Repository;
use App\Contracts\RoomContracts\RoomServiceContract;
use App\Http\DTO\Room\CreateRoomDTO;
use App\Http\DTO\Room\UpdateRoomDTO;
use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RoomService implements RoomServiceContract
{
    public function getPaginatedRooms(): LengthAwarePaginator
    {
        return Repository::room()->getPaginatedRooms();
    }

    public function getAllRooms(): Collection
    {
        return Repository::room()->getAllRooms();
    }

    public function getRoomById(int $id): ?Room
    {
        return Repository::room()->getRoomById($id);
    }

    public function createNewRoom(CreateRoomDTO $dto): Room
    {
        $data = $dto->toArray();
        return Repository::room()->createNewRoom($data);
    }

    public function updateRoomDetails(int $id, UpdateRoomDTO $dto): bool
    {
        $data = $dto->toArray();
        return Repository::room()->updateRoomDetails($id, $data);
    }

    public function deleteRoom(int $id): bool
    {
        return Repository::room()->deleteRoom($id);
    }

    public function getRoomsByHotelId(int $hotelId): Collection
    {
        return Repository::room()->getRoomsByHotelId($hotelId);
    }
}
