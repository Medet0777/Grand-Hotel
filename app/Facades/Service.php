<?php

namespace App\Facades;

use App\Contracts\UserContracts\OtpServiceContract;
use App\Contracts\UserContracts\UserServiceContract;
use App\Contracts\WishlistContracts\WishlistServiceContract;
use Illuminate\Support\Facades\Facade;


/**
 * @method UserServiceContract user()
 * @method OtpServiceContract otp()
 * @method WishlistServiceContract wishlist()
 */


class Service extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'service';
    }
}
