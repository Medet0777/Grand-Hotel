<?php

namespace App\Http\Requests\Hotel;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateHotelRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'price_per_night' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ];
    }

    public function toDTO(): \App\Http\DTO\Hotel\CreateHotelDTO
    {
        return \App\Http\DTO\Hotel\CreateHotelDTO::fromRequest($this);
    }
}
