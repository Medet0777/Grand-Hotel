<?php

namespace App\Contracts\UserContracts;

use Illuminate\Http\JsonResponse;
use App\Models\User;

interface UserServiceContract
{
    public function signUp(array $data): JsonResponse;

    public function signIn(array $data): JsonResponse;

    public function sendOtp(string $email): JsonResponse;

    public function resetPassword(string $email, string $newPassword): JsonResponse;
}
