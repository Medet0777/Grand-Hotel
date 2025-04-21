<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'new_password' => 'required|string|min:8|confirmed',
            'reset_token' => 'required|string', // Добавляем правило для временного токена
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Поле "Email" обязательно для заполнения.',
            'new_password.required' => 'Поле "Новый пароль" обязательно для заполнения.',
            'reset_token.required' => 'Временный токен для сброса пароля отсутствует.', // Сообщение для нового поля
        ];
    }
}
