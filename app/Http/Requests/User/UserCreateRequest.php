<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'nickname' => 'required|string|max:50',
            'phone_number' => 'required|string|unique:users,phone_number|regex:/^\+?[0-9]{10,15}$/',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Поле "Имя" обязательно для заполнения.',
            'email.required' => 'Поле "Email" обязательно для заполнения.',
            'password.required' => 'Поле "Пароль" обязательно для заполнения.',
        ];
    }
}
