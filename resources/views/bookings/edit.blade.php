<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Booking') }}
        </h2>
    </x-slot>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Edit Booking</h2>

        <form method="POST" action="{{ route('properties.bookings.update', [$property, $booking]) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="booking_reference" class="block text-sm font-semibold text-gray-700 mb-2">Booking Reference</label>
                <input type="text" id="booking_reference" name="booking_reference" value="{{ $booking->booking_reference }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" disabled>
            </div>

            <div class="mb-4">
                <label for="guest_name" class="block text-sm font-semibold text-gray-700 mb-2">Guest Name</label>
                <input type="text" id="guest_name" name="guest_name" value="{{ $booking->guest->full_name }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" disabled>
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <input type="text" id="status" name="status" value="{{ ucfirst(str_replace('_', ' ', $booking->status)) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" disabled>
            </div>

            <div class="mb-4">
                <label for="special_requests" class="block text-sm font-semibold text-gray-700 mb-2">Special Requests</label>
                <textarea id="special_requests" name="special_requests" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $booking->special_requests }}</textarea>
                @error('special_requests') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="check_in_date" class="block text-sm font-semibold text-gray-700 mb-2">Check-in Date</label>
                    <input type="date" id="check_in_date" name="check_in_date" value="{{ $booking->check_in_date }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" disabled>
                </div>
                <div>
                    <label for="check_out_date" class="block text-sm font-semibold text-gray-700 mb-2">Check-out Date</label>
                    <input type="date" id="check_out_date" name="check_out_date" value="{{ $booking->check_out_date }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" disabled>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="number_of_guests" class="block text-sm font-semibold text-gray-700 mb-2">Number of Guests</label>
                    <input type="number" id="number_of_guests" name="number_of_guests" value="{{ $booking->number_of_guests }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" disabled>
                </div>
                <div>
                    <label for="total_amount" class="block text-sm font-semibold text-gray-700 mb-2">Total Amount</label>
                    <input type="text" id="total_amount" name="total_amount" value="${{ number_format($booking->total_amount, 2) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" disabled>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Update Booking
                </button>
                <a href="{{ route('properties.bookings.show', [$property, $booking]) }}" class="flex-1 bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>

