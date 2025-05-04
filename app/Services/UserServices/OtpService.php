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

    public function generateAndSend(User|array $user, string $token): void
    {
        $otp = rand(1000, 9999);
        $this->storeOtp($user, $otp, $token);

        if (is_array($user)) {

            $fakeUser = new User();
            $fakeUser->email = $user['email'];
            $this->sendOtpEmail($fakeUser, $otp);
        } else {
            $this->sendOtpEmail($user, $otp);
        }
    }

    public function verify(User $user, string $otp): bool
    {
        $registrationToken = Cache::get("reg_token_for_email_" . $user->email);
        $cacheKey = $this->getCacheKey($user->email, $registrationToken);
        $cachedOtp = Cache::get($cacheKey);
        return $cachedOtp === $otp;
    }

    public function clear(User $user): void
    {
        $registrationToken = Cache::get("reg_token_for_email_" . $user->email);
        $cacheKey = $this->getCacheKey($user->email, $registrationToken);
        Cache::forget($cacheKey);
        Cache::forget("reg_token_for_email_" . $user->email); // Очищаем и токен
    }

    protected function storeOtp(User|array $user, string $otp, string $registrationToken = null): void
    {
        $email = is_array($user) ? $user['email'] : $user->email;
        $cacheKey = $this->getCacheKey($email, $registrationToken);
        Cache::put($cacheKey, $otp, $this->expirationTime);
        if ($registrationToken) {
            Cache::put("reg_token_for_email_" . $email, $registrationToken, $this->expirationTime);
        }
    }

    protected function sendOtpEmail(User $user, string $otp): void
    {
        Log::info("Sending OTP to $user->email with OTP: $otp");
        $user->notify(new OtpNotification($otp));
    }

    protected function getCacheKey(string $email, ?string $registrationToken = null): string
    {
        $baseKey = "otp_for_email_$email";
        return $registrationToken ? $baseKey . "_reg_" . $registrationToken : $baseKey;
    }
}

