<?php

namespace App\Http\Controllers\Api;

use App\Contracts\UserContracts\OtpServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\User;
use App\Services\UserServices\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected UserService $userService;
    protected OtpServiceContract $otpService;

    public function __construct(UserService $userService, OtpServiceContract $otpService)
    {
        $this->userService = $userService;
        $this->otpService = $otpService;
    }

    public function signUp(UserCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $this->userService->createUser($data);
        $this->otpService->generateAndSend($user);

        return response()->json([
            'message' => 'Registration successful. Please verify your email using the OTP sent to your address.',
            'user_id' => $user->id,
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
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $resetToken = $request->input('reset_token');

        if (!$this->userService->validateResetToken($user, $resetToken)) {
            return response()->json(['message' => 'Invalid or expired reset token'], 400);
        }

        $response = $this->userService->resetPassword($data['email'], $data['new_password']);
        $this->userService->clearResetToken($user);
        return $response;
    }

    public function logout(): JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function sendOtp(SendOtpRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->firstOrFail();
        $this->otpService->generateAndSend($user);
        return response()->json(['message' => 'OTP sent to your email']);
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $user = User::findOrFail($request->input('user_id'));

        if ($resetToken = $this->userService->verifyOtpForPasswordReset($user, $request->otp)) {
            return response()->json([
                'message' => 'OTP verified successfully. You can now reset your password.',
                'reset_token' => $resetToken,
                'user_id' => $user->id,
            ]);
        } else {
            return response()->json(['message' => 'Invalid OTP'], 422);
        }
    }

    public function verifyRegistrationOtp(VerifyOtpRequest $request): JsonResponse
    {
        $user = User::findOrFail($request->input('user_id'));
        return $this->userService->verifyRegistrationOtp($user, $request->otp);
    }
}
