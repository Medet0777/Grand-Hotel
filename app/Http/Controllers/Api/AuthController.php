<?php

namespace App\Http\Controllers\Api;

use App\Facades\Service;
use App\Facades\Repository;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\VerifyOtpRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{


    /**
     * @OA\Post(
     *     path="/api/signup",
     *     tags={"Auth"},
     *     summary="User Registration",
     *     description="Register a new user and send OTP to email",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful registration",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Registration successful. Please verify your email using the OTP sent to your address."),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     )
     * )
     */
    public function signUp(UserCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = Service::user()->createUser($data);
        Service::otp()->generateAndSend($user);

        return response()->json([
            'message' => 'Registration successful. Please verify your email using the OTP sent to your address.',
            'user_id' => $user->id,
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/signin",
     *     tags={"Auth"},
     *     summary="User Login",
     *     description="Authenticate user and return token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully logged in"),
     *             @OA\Property(property="token", type="string", example="Bearer eyJ0eXAiOiJKV1QiLCJhbGci..."),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function signIn(UserLoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        return Service::user()->signIn($data);
    }

    /**
     * @OA\Post(
     *     path="/api/reset-password",
     *     tags={"Auth"},
     *     summary="Reset Password",
     *     description="Reset user password using reset token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","reset_token","new_password"},
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="reset_token", type="string", example="a1b2c3d4e5f6"),
     *             @OA\Property(property="new_password", type="string", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password successfully updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password successfully updated"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=400, description="Invalid or expired reset token")
     * )
     */
    public function resetPassword(PasswordResetRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = Repository::user()->findByEmail($data['email']);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!Service::user()->validateResetToken($user, $data['reset_token'])) {
            return response()->json(['message' => 'Invalid or expired reset token'], 400);
        }

        $response = Service::user()->resetPassword($data['email'], $data['new_password']);
        Service::user()->clearResetToken($user);

        return $response;
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Logout",
     *     description="Logout authenticated user",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
     *         )
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        $user = Auth::user();
        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @OA\Post(
     *     path="/api/send-otp",
     *     tags={"Auth"},
     *     summary="Send OTP",
     *     description="Send OTP to user's email",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", example="john@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="OTP sent to your email")
     *         )
     *     )
     * )
     */
    public function sendOtp(SendOtpRequest $request): JsonResponse
    {
        $user = Repository::user()->findByEmail($request->email);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        Service::otp()->generateAndSend($user);
        return response()->json(['message' => 'OTP sent to your email']);
    }

    /**
     * @OA\Post(
     *     path="/api/verify-otp",
     *     tags={"Auth"},
     *     summary="Verify OTP for Password Reset",
     *     description="Verify OTP and get reset token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","otp"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="otp", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP verified",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="OTP verified successfully. You can now reset your password."),
     *             @OA\Property(property="reset_token", type="string", example="a1b2c3d4e5f6"),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=422, description="Invalid OTP")
     * )
     */

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $user = Repository::user()->findById($request->id);

        if ($resetToken = Service::user()->verifyOtpForPasswordReset($user, $request->otp)) {
            return response()->json([
                'message' => 'OTP verified successfully. You can now reset your password.',
                'reset_token' => $resetToken,
                'user_id' => $user->id,
            ]);
        } else {
            return response()->json(['message' => 'Invalid OTP'], 422);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/verify-registration-otp",
     *     tags={"Auth"},
     *     summary="Verify Registration OTP",
     *     description="Verify OTP for email confirmation and login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","otp"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="otp", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email verified and logged in",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Email verified successfully. You are now logged in."),
     *             @OA\Property(property="token", type="string", example="Bearer eyJ0eXAiOiJKV..."),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Invalid OTP")
     * )
     */

    public function verifyRegistrationOtp(VerifyOtpRequest $request): JsonResponse
    {
        $user = Repository::user()->findById($request->id);
        return Service::user()->verifyRegistrationOtp($user, $request->otp);
    }
}
