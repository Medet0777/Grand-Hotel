<?php

namespace App\Contracts\UserContracts;

use App\Models\User;
use Illuminate\Http\JsonResponse;


interface UserServiceContract
{

    public function signIn(array $data): JsonResponse;

    public function createUser(array $data): User;

    public function resetPassword(string $email, string $newPassword,): JsonResponse;

    public function verifyOtpForPasswordReset(User $user, string $otp): ?string;

    public function validateResetToken(User $user, string $resetToken): bool;

    public function verifyRegistrationOtp(User $user, string $otp): JsonResponse;

    public function clearResetToken(User $user): void;
}
