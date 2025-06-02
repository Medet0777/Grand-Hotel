<?php

namespace App\Contracts\UserContracts;

use App\Http\DTO\User\CreateUserDTO;
use App\Http\DTO\User\ResetPasswordDTO;
use App\Http\DTO\User\SignInDTO;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\User\VerifyRegistrationRequest;

interface AuthServiceContract
{
    public function initiateRegistration(CreateUserDTO $dto, string $registrationToken): string;

    public function verifyRegistration(VerifyRegistrationRequest $request): JsonResponse;

    public function signIn(SignInDTO $data): JsonResponse;

    public function resetPassword(ResetPasswordDTO $data): JsonResponse;

    public function verifyOtpForPasswordReset(User $user, string $otp): ?string;

    public function validateResetToken(User $user, string $resetToken): bool;

    public function clearResetToken(User $user): void;

    public function createUser(CreateUserDTO $data): User;

    public function verifyRegistrationOtp(User $user, string $otp): JsonResponse;
    public function isNicknameTaken(string $nickname): bool;
    public function isPhoneNumberTaken(string $phoneNumber): bool;
}
