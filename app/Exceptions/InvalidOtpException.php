<?php

namespace App\Exceptions;

class InvalidOtpException extends AbstractException
{
    protected int $statusCode = 422;

    protected function getDefaultMessage(): string
    {
        return 'Invalid OTP';
    }
}
