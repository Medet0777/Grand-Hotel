<?php

namespace App\Services\UserServices;

use App\Contracts\UserContracts\OtpServiceContract;
use App\Contracts\UserContracts\UserRepositoryContract;
use App\Contracts\UserContracts\UserServiceContract;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserService implements UserServiceContract
{
    protected UserRepositoryContract $userRepository;
    protected OtpServiceContract $otpService;

    public function __construct(UserRepositoryContract $userRepository, OtpServiceContract $otpService)
    {
        $this->userRepository = $userRepository;
        $this->otpService = $otpService;
    }

    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['email_verified_at'] = null;
        return $this->userRepository->create($data);
    }

    public function signIn(array $data): JsonResponse
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Successfully logged in',
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }

    public function resetPassword(string $email, string $newPassword): JsonResponse
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->password = Hash::make($newPassword);
        $this->userRepository->save($user);

        return response()->json(['message' => 'Password successfully updated', 'user' => new UserResource($user)]);
    }

    public function verifyOtpForPasswordReset(User $user, string $otp): ?string
    {
        if ($this->otpService->verify($user, $otp)) {
            $this->otpService->clear($user);
            $resetToken = Str::random(60);
            Cache::put("password_reset_token_" . $user->id, $resetToken, 3600);
            return $resetToken;
        }
        return null;
    }

    public function validateResetToken(User $user, string $resetToken): bool
    {
        $cachedToken = Cache::get("password_reset_token_" . $user->id);
        return $cachedToken === $resetToken;
    }

    public function clearResetToken(User $user): void
    {
        Cache::forget("password_reset_token_" . $user->id);
    }

    public function verifyRegistrationOtp(User $user, string $otp): JsonResponse
    {
        if ($this->otpService->verify($user, $otp)) {
            $this->otpService->clear($user);
            $user->markEmailAsVerified();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Email verified successfully. You are now logged in.',
                'token' => $token,
                'user' => new UserResource($user),
            ]);
        }
        return response()->json(['message' => 'Invalid OTP'], 422);
    }
}
