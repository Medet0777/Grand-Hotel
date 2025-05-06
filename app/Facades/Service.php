<?php

namespace App\Facades;

use App\Contracts\HotelContracts\HotelServiceContract;
use App\Contracts\RoomContracts\RoomServiceContract;
use App\Contracts\UserContracts\OtpServiceContract;
use App\Contracts\UserContracts\UserServiceContract;
use App\Contracts\WishlistContracts\WishlistServiceContract;
use App\Services\BookingServices\BookingService;
use App\Services\UserServices\AuthService;
use Illuminate\Support\Facades\Facade;


/**
 * @method UserServiceContract user()
 * @method OtpServiceContract otp()
 * @method WishlistServiceContract wishlist()
 * @method HotelServiceContract hotel()
 * @method RoomServiceContract room()
 * @method AuthService auth()
 * @method BookingService booking()
 */


class Service extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'service';
    }
}
