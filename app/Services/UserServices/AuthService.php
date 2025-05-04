<?php

namespace App\Services\UserServices;

use App\Contracts\UserContracts\AuthServiceContract;
use App\Exceptions\InvalidCredentialsException;
use App\Facades\Repository;
use App\Facades\Service;
use App\Http\DTO\User\CreateUserDTO;
use App\Http\DTO\User\ResetPasswordDTO;
use App\Http\DTO\User\SignInDTO;
use App\Http\Requests\User\VerifyRegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthService implements AuthServiceContract
{
    public function initiateRegistration(CreateUserDTO $dto, string $registrationToken): string
    {

        $userData = [
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
            'nickname' => $dto->nickname,
            'phone_number' => $dto->phone_number,
        ];

        if (Service::auth()->isNicknameTaken($userData['nickname'])) {
            throw ValidationException::withMessages(['nickname' => ['Никнейм уже занят.']]);
        }
        if (Service::auth()->isPhoneNumberTaken($userData['phone_number'])) {
            throw ValidationException::withMessages(['phone_number' => ['Номер телефона уже используется.']]);
        }

        Cache::put('registration:' . $registrationToken, $userData, now()->addMinutes(60));
        $user = ['email' => $dto->email];
        Service::otp()->generateAndSend($user, $registrationToken);
        return $registrationToken;
    }

    public function verifyRegistration(VerifyRegistrationRequest $request): JsonResponse
    {
        $otp = $request->otp;
        $registrationToken = $request->registration_token;


        $userData = Cache::get('registration:' . $registrationToken);

        if (!$userData) {
            return response()->json(['message' => 'Недействительный токен регистрации или email.'], 422);
        }

        $createUserDTO = new CreateUserDTO(
            name: $userData['name'],
            email: $userData['email'],
            password: $userData['password'],
            phone_number: $userData['phone_number'] ?? null,
            nickname: $userData['nickname'] ?? null,
        );

        $newUser = Service::auth()->createUser($createUserDTO);

        Cache::forget('registration:' . $registrationToken);
        Service::otp()->clear($newUser);

        $token = $newUser->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Email успешно подтвержден. Ваша учетная запись была создана.',
            'token' => $token,
            'user' => $newUser,
        ], 201);
    }

    public function signIn(SignInDTO $data): JsonResponse
    {
        $user = Repository::user()->findByEmail($data->email);

        if (!$user || !Hash::check($data->password, $user->password)) {
            throw new InvalidCredentialsException();
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

    public function isNicknameTaken(string $nickname): bool
    {
        return User::where('nickname', $nickname)->exists();
    }

    public function isPhoneNumberTaken(string $phoneNumber): bool
    {
        return User::where('phone_number', $phoneNumber)->exists();
    }

    public function createUser(CreateUserDTO $data): User
    {
        return Repository::user()->create($data);
    }

}
