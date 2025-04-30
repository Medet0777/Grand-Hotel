<?php

namespace App\Http\DTO\User;



use App\Http\Requests\User\UpdateUserRequest;

class UpdateUserDTO
{
    public string $name;
    public string $nickname;
    public string $phone_number;

   public function __construct(string $name, string $nickname, string $phone_number)
   {
       $this->name = $name;
       $this->nickname = $nickname;
       $this->phone_number = $phone_number;
   }

    public static function fromRequest(UpdateUserRequest $request): self
    {
        return new self(
            $request->input('name'),
            $request->input('nickname'),
            $request->input('phone_number')
        );
    }
}
