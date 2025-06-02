<?php

namespace App\Http\Requests\Hotel;
use Illuminate\Foundation\Http\FormRequest;

class SearchHotelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'query' => 'required|string|min:1|max:255',
        ];
    }
}
