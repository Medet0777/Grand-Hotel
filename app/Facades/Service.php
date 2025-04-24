<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Service extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'service';
    }
}
