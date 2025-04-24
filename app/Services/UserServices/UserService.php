<?php

namespace App\Services\UserServices;

use App\Facades\Repository;
use App\Facades\Service;
use App\Contracts\UserContracts\UserServiceContract;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserService implements UserServiceContract
{


    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['email_verified_at'] = null;
        return Repository::user()->create($data);
    }

    public function signIn(array $data): JsonResponse
    {
        $user = Repository::user()->findByEmail($data['email']);

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
        $user = Repository::user()->findByEmail($email);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->password = Hash::make($newPassword);
        Repository::user()->save($user);

        return response()->json(['message' => 'Password successfully updated', 'user' => new UserResource($user)]);
    }

    public function verifyOtpForPasswordReset(User $user, string $otp): ?string
    {
        if (Service::otp()->verify($user, $otp)) {
            Service::otp()->clear($user);
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
        if (Service::otp()->verify($user, $otp)) {
            Service::otp()->clear($user);
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
