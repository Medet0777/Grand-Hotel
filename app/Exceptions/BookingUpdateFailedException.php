<?php

namespace App\Exceptions;

class BookingUpdateFailedException extends AbstractException
{
    protected int  $statusCode = 500;

    protected function getDefaultMessage(): string
    {
        return 'Failed to update booking';
    }
}
