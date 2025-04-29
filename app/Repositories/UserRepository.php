<?php

namespace App\Repositories;

use App\Contracts\UserContracts\UserRepositoryContract;
use App\Http\DTO\User\CreateUserDTO;
use App\Http\DTO\User\UpdateUserDTO;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryContract
{
    public function all(): Collection
    {
        return User::all();
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findById(int $id): User
    {
        return User::findOrFail($id);
    }

    public function create(CreateUserDTO $dto): User
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => bcrypt($dto->password),
            'nickname' => $dto->nickname,
            'phone_number' => $dto->phone_number,
        ]);
    }

    public function update(int $id, UpdateUserDTO $dto): bool
    {
        $user = User::findOrFail($id);
        return $user->update([
            'name' => $dto->name,
            'nickname' => $dto->nickname,
            'phone_number' => $dto->phone_number,
        ]);
    }

    public function delete(int $id): bool
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }

    public function save(User $user): void
    {
        $user->save();
    }
}
