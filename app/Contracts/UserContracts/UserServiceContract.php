<?php

namespace App\Contracts\UserContracts;

use App\Http\DTO\User\UpdateUserDTO;
use App\Http\DTO\User\UploadAvatarDTO;


interface UserServiceContract
{
    public function uploadAvatar(UploadAvatarDTO $dto,int $userId): void;

    public function getUserDataWithAvatar(int $userId): array;

    public function updateUser(int $id, UpdateUserDTO $dto): bool;


}
