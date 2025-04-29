<?php

namespace App\Http\Requests\Hotel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHotelRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|min:0|max:5',
            'price_per_night' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ];
    }

    public function toDTO(): \App\Http\DTO\Hotel\UpdateHotelDTO
    {
        return \App\Http\DTO\Hotel\UpdateHotelDTO::fromRequest($this);
    }
}
