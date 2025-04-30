<?php

namespace App\Facades;

use App\Contracts\HotelContracts\HotelServiceContract;
use App\Contracts\RoomContracts\RoomServiceContract;
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
 */


class Service extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'service';
    }
}
