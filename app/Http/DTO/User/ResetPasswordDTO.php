<?php
namespace App\Http\DTO\User;

use App\Http\Requests\PasswordResetRequest;

class ResetPasswordDTO
{
    public string $email;
    public string $newPassword;
    public string $resetToken;

    public function __construct(string $email, string $newPassword, string $resetToken)
    {
        $this->email = $email;
        $this->newPassword = $newPassword;
        $this->resetToken = $resetToken;
    }

    public static function fromRequest(PasswordResetRequest $request): self
    {
        return new self(
            $request->input('email'),
            $request->input('new_password'),
            $request->input('reset_token')
        );
    }
}
