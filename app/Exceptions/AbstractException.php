<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

abstract class AbstractException extends Exception
{
    protected int $statusCode = 200;

    public function __construct(string $message = null)
    {
        parent::__construct($message ?? $this->getDefaultMessage(), $this->statusCode);

    }

    abstract protected function getDefaultMessage(): string;

    public function render(): JsonResponse
    {
        return response()->json([
            'error' => $this->getMessage(),
            'code'  => $this->statusCode,
        ], $this->statusCode);
    }
}
