<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'guest_id' => 'required|exists:guests,id',
            'room_ids' => 'required|array|min:1',
            'room_ids.*' => 'exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1',
            'room_rate' => 'nullable|numeric|min:0',
            'room_rates' => 'nullable|array',
            'room_rates.*' => 'numeric|min:0',
            'special_requests' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'guest_id.required' => 'Please select a guest',
            'room_ids.required' => 'Please select at least one room',
            'room_ids.min' => 'Please select at least one room',
            'check_in_date.after_or_equal' => 'Check-in date must be today or in the future',
            'check_out_date.after' => 'Check-out date must be after check-in date',
            'number_of_guests.required' => 'Please enter number of guests',
        ];
    }
}

