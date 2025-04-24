<?php

namespace App\Exceptions;

class InvalidResetTokenException extends AbstractException
{
    protected int $statusCode = 400;

    protected function getDefaultMessage(): string
    {
        return 'Invalid or expired reset token';
    }
}

