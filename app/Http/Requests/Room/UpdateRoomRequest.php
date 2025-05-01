<?php

namespace App\Http\Requests\Room;

use App\Http\DTO\Room\UpdateRoomDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
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
            'hotel_id' => 'sometimes|integer|exists:hotels,id',
            'room_type' => 'sometimes|string|max:255',
            'price_per_night' => 'sometimes|numeric|min:0',
            'available' => 'sometimes|boolean',
        ];
    }

    public function toDTO(): UpdateRoomDTO
    {
        return new UpdateRoomDTO(
            hotel_id: $this->input('hotel_id'),
            room_type: $this->input('room_type'),
            price_per_night: $this->input('price_per_night'),
            available: $this->input('available'),
        );
    }
}
