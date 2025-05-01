<?php

namespace App\Http\DTO\Room;

use Spatie\DataTransferObject\DataTransferObject;

class CreateRoomDTO extends DataTransferObject
{
    public int $hotel_id;
    public string $room_type;
    public float $price_per_night;
    public bool $available;
}
