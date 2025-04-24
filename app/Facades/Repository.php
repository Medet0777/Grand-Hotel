<?php
namespace App\Facades;

use App\Contracts\UserContracts\UserRepositoryContract;
use Illuminate\Support\Facades\Facade;

/**
 * @method UserRepositoryContract user()
 */

class Repository extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'repository';
    }
}
