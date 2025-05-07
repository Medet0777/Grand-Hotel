<?php

namespace App\Http\Requests\Hotel;

use App\Http\DTO\Hotel\FilterHotelDTO;
use Illuminate\Foundation\Http\FormRequest;

class FilterHotelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'rating' => 'nullable|integer|min:1|max:5',
        ];
    }

    public function toDTO(): FilterHotelDTO
    {
        $data = $this->validated();

        return new FilterHotelDTO(
            min_price: $data['min_price'] ?? null,
            max_price: $data['max_price'] ?? null,
            rating: $data['rating'] ?? null,
        );
    }
}
