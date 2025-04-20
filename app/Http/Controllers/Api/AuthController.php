<?php

namespace App\Http\Controllers\Api;

use App\Contracts\UserContracts\OtpServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserServices\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    protected UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function signUp(UserCreateRequest $request, OtpServiceContract $otpService): JsonResponse
    {
        $data = $request->validated();
        $user = $this->userService->createUser($data); // Изменим название метода в UserService

        $otpService->generateAndSend($user);

        return response()->json([
            'message' => 'Registration successful. Please verify your email using the OTP sent to your address.',
            'user_id' => $user->id, // Можем вернуть ID пользователя для связи с OTP на фронтенде
            // 'verification_token' => 'временный_токен', // Альтернативный вариант временного токена
        ], 201);
    }

    public function signIn(UserLoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        return $this->userService->signIn($data);
    }

    public function resetPassword(PasswordResetRequest $request): JsonResponse
    {
        $data = $request->validated();

        return $this->userService->resetPassword($data['email'], $data['new_password']);
    }

    public function logout(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function sendOtp(SendOtpRequest $request, OtpServiceContract $otpService): JsonResponse
    {
        $user = User::where('email', $request->email)->firstOrFail();

        $otpService->generateAndSend($user);

        return response()->json(['message' => 'OTP sent to your email']);
    }

    public function verifyOtp(VerifyOtpRequest $request, OtpServiceContract $otpService): JsonResponse
    {
        $user = User::findOrFail($request->input('user_id'));

        if ($otpService->verify($user, $request->otp)) {
            $otpService->clear($user); // Очищаем OTP после успешной верификации
            return response()->json(['message' => 'OTP verified successfully']);
        } else {
            return response()->json(['message' => 'Invalid OTP'], 422); // 422 Unprocessable Entity
        }
    }

    public function verifyRegistrationOtp(VerifyOtpRequest $request, OtpServiceContract $otpService): JsonResponse
    {
        $user = User::findOrFail($request->input('user_id')); // Предполагаем, что фронтенд отправляет user_id

        if ($otpService->verify($user, $request->otp)) {
            $otpService->clear($user);
            $user->markEmailAsVerified(); // Добавь это в свою модель User, если еще нет
            // Можно сразу залогинить пользователя после верификации
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Email verified successfully. You are now logged in.',
                'token' => $token,
                'user' => new UserResource($user)
            ]);
        } else {
            return response()->json(['message' => 'Invalid OTP'], 422);
        }
    }


}
