<?php

namespace App\Exceptions;

class InvalidCredentialsException extends AbstractException
{
    protected int $statusCode = 401;

    protected function getDefaultMessage(): string
    {
        return 'Invalid email or password';
    }
}
