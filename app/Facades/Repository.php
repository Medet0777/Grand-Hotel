<?php
namespace App\Facades;

use App\Contracts\UserContracts\UserRepositoryContract;
use App\Contracts\WishlistContracts\WishlistRepositoryContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method UserRepositoryContract user()
 * @method WishlistRepositoryContract wishlist()
 */

class Repository extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'repository';
    }
}
