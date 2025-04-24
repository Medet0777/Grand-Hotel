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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\DTO\User\CreateUserDTO;
use App\Http\DTO\User\SignInDTO;
use App\Http\DTO\User\ResetPasswordDTO;


class UserService implements UserServiceContract
{


    public function createUser(CreateUserDTO $data): User
    {
        return Repository::user()->create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
            'email_verified_at' => null
        ]);
    }

    public function signIn(SignInDTO $data): JsonResponse
    {
        $user = Repository::user()->findByEmail($data->email);

        if (!$user || !Hash::check($data->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Successfully logged in',
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }

    public function resetPassword(ResetPasswordDTO $data): JsonResponse
    {
        $user = Repository::user()->findByEmail($data->email);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        $user->password = Hash::make($data->newPassword);
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
