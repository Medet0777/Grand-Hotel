<?php
namespace App\Facades;

use App\Contracts\HotelContracts\HotelRepositoryContract;
use App\Contracts\RoomContracts\RoomRepositoryContract;
use App\Contracts\UserContracts\UserRepositoryContract;
use App\Contracts\WishlistContracts\WishlistRepositoryContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method UserRepositoryContract user()
 * @method WishlistRepositoryContract wishlist()
 * @method HotelRepositoryContract hotel()
 * @method RoomRepositoryContract room()
 */

class Repository extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'repository';
    }
}
