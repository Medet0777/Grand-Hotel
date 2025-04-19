<?php

namespace App\Services\UserServices;

use AllowDynamicProperties;
use App\Contracts\UserContracts\UserServiceContract;
use App\Contracts\UserContracts\UserRepositoryContract;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;


#[AllowDynamicProperties] class UserService implements UserServiceContract
{
    public function __construct(UserRepositoryContract $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function signUp(array $data): JsonResponse
    {
        $data['password'] = Hash::make($data['password']);

        $user = $this->userRepository->create($data);

        return response()->json([
           'message' => 'User Created Successfully',
           'user' => new  UserResource($user),
        ],201);
    }

    public function signIn(array $data): JsonResponse
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if(!$user || !Hash::check($data['password'], $user->password)){
            return response()->json(['message' => 'Invalid credentials'],401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Successfully logged in',
            'token' => $token,
            'user' => new UserResource($user)
        ]);
    }

    public function sendOtp(string $email): JsonResponse
    {
        $otp = rand(1000,9999);

        return response()->json(['otp' => $otp]);
    }

    public function resetPassword(string $email, string $newPassword): JsonResponse
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        return response()->json([
            'message' => 'Password successfully updated',
            'user' => new UserResource($user)
        ]);
    }
}
