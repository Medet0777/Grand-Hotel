<?php
namespace App\Facades;

use App\Contracts\BookingContracts\BookingRepositoryContract;
use App\Contracts\HotelContracts\HotelRepositoryContract;
use App\Contracts\ReviewContarcts\ReviewRepositoryContract;
use App\Contracts\RoomContracts\RoomRepositoryContract;
use App\Contracts\UserContracts\UserRepositoryContract;
use App\Contracts\WishlistContracts\WishlistRepositoryContract;
use Illuminate\Support\Facades\Facade;


/**
 * @method UserRepositoryContract user()
 * @method WishlistRepositoryContract wishlist()
 * @method HotelRepositoryContract hotel()
 * @method RoomRepositoryContract room()
 * @method BookingRepositoryContract booking()
 * @method ReviewRepositoryContract review()
 */

class Repository extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'repository';
    }
}
