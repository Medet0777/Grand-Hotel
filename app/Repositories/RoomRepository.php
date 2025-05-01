<?php

namespace App\Repositories;

use App\Contracts\RoomContracts\RoomRepositoryContract;
use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RoomRepository implements RoomRepositoryContract
{

    public function getPaginatedRooms(): LengthAwarePaginator
    {
        return Room::paginate();
    }

    public function getAllRooms(): Collection
    {
        return Room::all();
    }

    public function getRoomById(int $id): ?Room
    {
        return Room::find($id);
    }

    public function createNewRoom(array $data): Room
    {
        return Room::create($data);
    }

    public function updateRoomDetails(int $id, array $data): bool
    {
        $room = Room::find($id);

        if(!$room){
            return false;
        }

        return $room->update($data);
    }

    public function deleteRoom(int $id): bool
    {
        $room = Room::find($id);

        if(!$room){
            return false;
        }

        return $room->delete();
    }

    public function getRoomsByHotelId(int $hotelId): Collection
    {
        return Room::where('hotel_id', $hotelId)->get();
    }
}
