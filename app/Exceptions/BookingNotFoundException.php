<?php

namespace App\Exceptions;

class BookingNotFoundException extends AbstractException
{
    protected int $statusCode = 404;

    protected function getDefaultMessage(): string
    {
        return 'Booking not found';
    }
}
