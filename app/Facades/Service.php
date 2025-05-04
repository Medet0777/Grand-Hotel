<?php

namespace App\Facades;

use App\Contracts\BookingContracts\BookingServiceContract;
use App\Contracts\HotelContracts\HotelServiceContract;
use App\Contracts\ReviewContarcts\ReviewServiceContract;
use App\Contracts\RoomContracts\RoomServiceContract;
use App\Contracts\UserContracts\AuthServiceContract;
use App\Contracts\UserContracts\OtpServiceContract;
use App\Contracts\UserContracts\UserServiceContract;
use App\Contracts\WishlistContracts\WishlistServiceContract;

use Illuminate\Support\Facades\Facade;


/**
 * @method UserServiceContract user()
 * @method OtpServiceContract otp()
 * @method WishlistServiceContract wishlist()
 * @method HotelServiceContract hotel()
 * @method RoomServiceContract room()
 * @method AuthServiceContract auth()
 * @method BookingServiceContract booking()
 * @method ReviewServiceContract review()
 */


class Service extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'service';
    }
}
