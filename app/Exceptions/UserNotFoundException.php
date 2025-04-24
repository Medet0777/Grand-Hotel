<?php

namespace App\Exceptions;

class UserNotFoundException extends AbstractException
{
    protected int $statusCode = 404;

    protected function getDefaultMessage(): string
    {
        return 'User not found';
    }
}
