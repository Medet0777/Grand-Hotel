<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Http\DTO\Review\CreateReviewDTO;

class CreateReviewRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'hotel_id' => 'required|integer|exists:hotels,id',
            'rating' => [
                'required',
                'numeric',
                'min:1',
                'max:5',
                Rule::in([1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5]),
            ],
            'description' => 'required|string|max:500',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'rating.in' => 'The rating must be one of the following values: 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5.',
            'description.max' => 'The description must not exceed 500 characters.',
        ];
    }

    /**
     * Convert the validated request data to a DTO.
     */

    public function toDTO(): CreateReviewDTO
    {
        Log::debug('Request data: ', $this->all());
        return new CreateReviewDTO(
            user_id: (int) $this->input('user_id'),
            hotel_id: (int) $this->input('hotel_id'),
            rating: (float) $this->input('rating'),
            description: $this->input('description'),
        );
    }
}
