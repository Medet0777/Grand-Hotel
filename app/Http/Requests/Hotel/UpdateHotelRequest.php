<?php

namespace App\Http\Requests\Hotel;

use App\Http\DTO\Hotel\UpdateHotelDTO;
use Illuminate\Foundation\Http\FormRequest;


class UpdateHotelRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'location_name' => 'sometimes|required|string|max:255',
            'latitude' => 'sometimes|required|numeric|min:-90|max:90',
            'longitude' => 'sometimes|required|numeric|min:-180|max:180',
            'rating' => 'nullable|numeric|min:0|max:5',
            'price_per_night' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ];
    }


    public function toDTO(): UpdateHotelDTO
    {
        return new UpdateHotelDTO(
            name: $this->input('name'),
            location_name: $this->input('location_name'),
            latitude: $this->input('latitude'),
            longitude: $this->input('longitude'),
            rating: $this->input('rating'),
            price_per_night: $this->input('price_per_night'),
            description: $this->input('description'),
        );
    }
}
