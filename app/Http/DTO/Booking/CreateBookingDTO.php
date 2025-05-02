<?php
namespace App\Http\DTO\Booking;

use Spatie\DataTransferObject\DataTransferObject;

class CreateBookingDTO extends DataTransferObject
{
    public int $user_id;
    public int $hotel_id;
    public int $room_id;
    public string $check_in_date;
    public string $check_out_date;
    public string $status;
}
