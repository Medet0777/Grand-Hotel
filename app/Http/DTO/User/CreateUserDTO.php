<?php

namespace App\Http\DTO\User;

use App\Http\Requests\UserCreateRequest;

class CreateUserDTO
{
    public string $name;
    public string $email;
    public string $password;
    public string $phone_number;
    public string $nickname;

    public function __construct(string $name, string $email, string $password, string $phone_number, string $nickname)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->phone_number = $phone_number;
        $this->nickname = $nickname;
    }

    public static function fromRequest(UserCreateRequest $request): self
    {
        return new self(
            $request->input('name'),
            $request->input('email'),
            $request->input('password'),
            $request->input('phone_number'),
            $request->input('nickname')
        );
    }
}
