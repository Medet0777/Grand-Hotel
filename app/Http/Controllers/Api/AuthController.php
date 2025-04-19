<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Services\UserServices\UserService;
use Illuminate\Http\JsonResponse;


class AuthController extends Controller
{
    protected UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function signUp(UserCreateRequest $request): JsonResponse
    {
        $data = $request->validated();
        return $this->userService->signup($data);
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


}
