<?php

namespace App\Http\Requests\Room;

use App\Http\DTO\Room\CreateRoomDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateRoomRequest extends FormRequest
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
            'hotel_id' => 'required|exists:hotels,id',
            'room_type' => 'required|string',
            'price_per_night' => 'required|numeric',
            'available' => 'required|boolean',

        ];
    }

    public function toDTO(): CreateRoomDTO
    {
        return new CreateRoomDTO(
            hotel_id: $this->input('hotel_id'),
            room_type: $this->input('room_type'),
            price_per_night: $this->input('price_per_night'),
            available: $this->input('available'),
        );
    }

}
