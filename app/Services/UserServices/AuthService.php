<?php

namespace App\Services\UserServices;

use App\Facades\Service;
use App\Http\DTO\User\CreateUserDTO;
use App\Http\Requests\User\VerifyRegistrationRequest;
use Illuminate\Support\Facades\Cache;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AuthService
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

        if (Service::user()->isNicknameTaken($userData['nickname'])) {
            throw ValidationException::withMessages(['nickname' => ['Никнейм уже занят.']]);
        }
        if (Service::user()->isPhoneNumberTaken($userData['phone_number'])) {
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

        $newUser = Service::user()->createUser($createUserDTO);

        Cache::forget('registration:' . $registrationToken);
        Service::otp()->clear($newUser);

        $token = $newUser->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Email успешно подтвержден. Ваша учетная запись была создана.',
            'token' => $token,
            'user' => $newUser,
        ], 201);
    }
}
