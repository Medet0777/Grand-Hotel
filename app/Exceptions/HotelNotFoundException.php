<?php

namespace App\Exceptions;

class HotelNotFoundException extends AbstractException
{
    protected int $statusCode = 404;

    protected function getDefaultMessage(): string
    {
        return 'Hotel not found.';
    }
}
