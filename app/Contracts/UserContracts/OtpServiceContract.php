<?php

namespace App\Contracts\UserContracts;

use App\Models\User;

interface OtpServiceContract
{
    public function generateAndSend(User|array $user, string $token): void;
    public function verify(User $user, string $otp):bool;
    public function clear(User $user): void;
//
}
