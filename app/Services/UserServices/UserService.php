<?php

namespace App\Services\UserServices;


use App\Facades\Repository;

use App\Contracts\UserContracts\UserServiceContract;
use App\Http\DTO\User\UpdateUserDTO;
use App\Http\DTO\User\UploadAvatarDTO;
use App\Models\File;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;


class UserService implements UserServiceContract
{
    public function updateUser(int $id, UpdateUserDTO $dto): bool
    {
        return Repository::user()->update($id, $dto);
    }

    public function uploadAvatar(UploadAvatarDTO $dto, int $userId): void
    {
        try {
            DB::beginTransaction();

            $fileData = base64_decode($dto->avatar, true);

            if ($fileData === false) {
                throw new Exception('Invalid base64 avatar string.');
            }

            $file = new File();
            $file->data = base64_encode($fileData);
            $file->mime_type = $dto->mime_type;
            $file->name = $dto->name ?? 'avatar.jpg';
            $file->save();


            $user = Repository::user()->findById($userId);
            $user->avatar_id = $file->id;
            $user->save();

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Avatar upload error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getUserDataWithAvatar(int $userId): array
    {
        $user = Repository::user()->findWithAvatar($userId);

        $avatarData = null;
        $avatarMimeType = null;
        $avatarName = null;

        if ($user->avatar) {
            $avatarData = base64_encode($user->avatar->data);
            $avatarMimeType = $user->avatar->mime_type;
            $avatarName = $user->avatar->name;
        }

        $userData = [
            'name' => $user->name,
            'nickname' => $user->nickname,
            'avatar' => $avatarData ? [
                'data' => $avatarData,
                'mime_type' => $avatarMimeType,
                'name' => $avatarName,
            ] : null,
        ];

        return $userData;
    }
}
