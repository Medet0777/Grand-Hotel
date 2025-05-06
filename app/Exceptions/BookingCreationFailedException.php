<?php

namespace App\Exceptions;

class BookingCreationFailedException extends AbstractException
{
    protected int $statusCode = 500;

    protected function getDefaultMessage(): string
    {
        return 'Failed to create booking';
    }
}
