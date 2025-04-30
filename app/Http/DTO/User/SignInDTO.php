<?php

namespace App\Http\DTO\User;

use App\Http\Requests\User\UserLoginRequest;

class SignInDTO
{
    public string $email;
    public string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public static function fromRequest(UserLoginRequest $request): self{
        return new self(
            $request->input('email'),
            $request->input('password')
        );
    }
}
