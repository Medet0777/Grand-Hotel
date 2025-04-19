<?php

namespace App\Contracts\UserContracts;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;

interface UserRepositoryContract
{
    public function all(): Collection;
    public function findByEmail(string $email): ?User;

    public function create(array $data): User;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
