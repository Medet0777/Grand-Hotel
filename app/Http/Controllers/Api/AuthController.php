<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InvalidOtpException;
use App\Exceptions\InvalidResetTokenException;
use App\Exceptions\UserNotFoundException;
use App\Facades\Repository;
use App\Facades\Service;
use App\Http\Controllers\Controller;
use App\Http\DTO\User\CreateUserDTO;
use App\Http\DTO\User\ResetPasswordDTO;
use App\Http\DTO\User\SignInDTO;
use App\Http\DTO\User\UpdateUserDTO;
use App\Http\Requests\User\PasswordResetRequest;
use App\Http\Requests\User\SendOtpRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\VerifyOtpRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\User\VerifyRegistrationRequest;

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
     *             @OA\Property(property="password_confirmation", type="string", example="password123"),
     *             @OA\Property(property="nickname", type="string", example="johnny"),
     *             @OA\Property(property="phone_number", type="string", example="+77001234567")
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
        $dto = CreateUserDTO::fromRequest($request);
        $registrationToken = Service::auth()->initiateRegistration($dto);

        return response()->json([
            'message' => 'OTP sent to your email for verification.',
            'registration_token' => $registrationToken,
        ], 200);
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
        $dto = SignInDTO::fromRequest($request);
        return Service::user()->signIn($dto);
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
        $dto = ResetPasswordDTO::fromRequest($request);

        $user = Repository::user()->findByEmail($dto->email);
        if (!$user) {
           throw new UserNotFoundException();
        }

        if (!Service::user()->validateResetToken($user, $dto->resetToken)) {
            throw new InvalidResetTokenException();
        }

        $response = Service::user()->resetPassword($dto);
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
            throw new UserNotFoundException();
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
        $user = Repository::user()->findById($request->user_id);

        if (!$user) {
            throw new UserNotFoundException();
        }

        $resetToken = Service::user()->verifyOtpForPasswordReset($user, $request->otp);

        if (!$resetToken) {
            throw new InvalidOtpException();
        }

        return response()->json([
            'message' => 'OTP verified successfully. You can now reset your password.',
            'reset_token' => $resetToken,
            'user_id' => $user->id,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/profile",
     *     summary="Update user profile",
     *     description="Allows the authenticated user to update their name, nickname, and phone number. Email cannot be changed.",
     *     operationId="updateUserProfile",
     *     tags={"User"},
     *     security={{"sanctum": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "nickname", "phone_number"},
     *             @OA\Property(property="name", type="string", example="Gustavo"),
     *             @OA\Property(property="nickname", type="string", example="gus123"),
     *             @OA\Property(property="phone_number", type="string", example="+19003430")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Profile updated successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"phone_number": {"The phone number has already been taken."}}
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */

    public function updateProfile(UpdateUserRequest $request)
    {
        $dto = new UpdateUserDTO(
            name: $request->name,
            nickname: $request->nickname,
            phone_number: $request->phone_number
        );

        $userId = auth()->id();

        Service::user()->updateUser($userId, $dto);

        return response()->json(['message' => 'Profile updated successfully']);
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

    public function verifyRegistrationOtp(VerifyRegistrationRequest $request): JsonResponse
    {
        return Service::auth()->verifyRegistration($request);
    }

}
