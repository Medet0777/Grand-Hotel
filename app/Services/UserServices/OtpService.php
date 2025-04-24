<?php

namespace App\Services\UserServices;

use App\Contracts\UserContracts\OtpServiceContract;
use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class OtpService implements OtpServiceContract
{
    protected int $expirationTime = 600;
    public function generateAndSend(User $user): void
    {
        $otp = rand(1000, 9999);
        $this->storeOtp($user, $otp);
        $this->sendOtpEmail($user, $otp);
    }

    public function verify(User $user, string $otp): bool
    {
        $cachedOtp = Cache::get($this->getCacheKey($user));
        return hash_equals($cachedOtp, $otp);
    }

    public function clear(User $user): void
    {
        Cache::forget($this->getCacheKey($user));
    }

    protected function storeOtp(User $user, string $otp): void
    {
        Cache::put($this->getCacheKey($user), $otp, $this->expirationTime);
    }

    protected function sendOtpEmail(User $user, string $otp): void
    {
        Log::info("Sending OTP to $user->email with OTP: $otp");
        $user->notify(new OtpNotification($otp));
    }

    protected function getCacheKey(User $user): string
    {
        return "otp_for_user_$user->id";
    }
}

