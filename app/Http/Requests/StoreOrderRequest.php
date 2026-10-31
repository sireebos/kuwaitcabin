<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'restaurant_table_id' => 'nullable|exists:restaurant_tables,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'type' => 'required|in:dine_in,takeaway,room_service',
        ];
    }
}

