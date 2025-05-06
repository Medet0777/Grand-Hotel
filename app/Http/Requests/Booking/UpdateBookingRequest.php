<?php

namespace App\Http\Requests\Booking;

use App\Http\DTO\Booking\UpdateBookingDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'hotel_id' => 'required|exists:hotels,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'status' => 'nullable|in:pending,confirmed,cancelled',
        ];
    }

    public function toDTO(): UpdateBookingDTO
    {
        return new UpdateBookingDTO(
            user_id: $this->input('user_id'),
            hotel_id: $this->input('hotel_id'),
            room_id: $this->input('room_id'),
            check_in_date: $this->input('check_in_date'),
            check_out_date: $this->input('check_out_date'),
            status: $this->input('status', 'pending')
        );
    }
}
