<?php

namespace App\Exceptions;

class BookingDeletionFailedException extends AbstractException
{
    protected int $statusCode = 500;

    protected function getDefaultMessage(): string
    {
        return 'Failed to delete booking';
    }
}
