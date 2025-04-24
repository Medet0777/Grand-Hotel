<?php

namespace App\Contracts\UserContracts;
use App\Http\DTO\User\CreateUserDTO;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;

interface UserRepositoryContract
{
    public function all(): Collection;
    public function findByEmail(string $email): ?User;

    public function findById(int $id): ?User;

    public function create(CreateUserDTO $dto): User;

    public function update(int $id, CreateUserDTO $dto): bool;

    public function delete(int $id): bool;
}
