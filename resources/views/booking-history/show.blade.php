<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking History Details') }}
        </h2>
    </x-slot>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <!-- Header -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Guest Name</h3>
                <p class="text-2xl font-bold">{{ $bookingHistory->guest->full_name }}</p>
            </div>
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Booking Reference</h3>
                <p class="text-lg font-semibold">{{ $bookingHistory->booking_reference }}</p>
            </div>
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Room</h3>
                <p class="text-lg font-semibold">{{ $bookingHistory->room->room_number ?? 'N/A' }}</p>
            </div>
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Currency</h3>
                <p class="text-lg font-semibold">{{ $bookingHistory->currency }}</p>
            </div>
        </div>

        <hr class="my-6">

        <!-- Booking Details -->
        <h3 class="text-lg font-bold mb-4">Booking Details</h3>
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-gray-600 text-sm">Check-in Date</p>
                <p class="font-semibold">{{ $bookingHistory->check_in_date->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Check-out Date</p>
                <p class="font-semibold">{{ $bookingHistory->check_out_date->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Number of Nights</p>
                <p class="font-semibold">{{ $bookingHistory->number_of_nights }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Number of Guests</p>
                <p class="font-semibold">{{ $bookingHistory->number_of_guests }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Checked Out At</p>
                <p class="font-semibold">{{ $bookingHistory->checked_out_at->format('M d, Y H:i A') }}</p>
            </div>
        </div>

        <hr class="my-6">

        <!-- Financial Summary -->
        <h3 class="text-lg font-bold mb-4">Financial Summary</h3>
        <div class="flex justify-end mb-6">
            <div class="w-80 bg-gray-50 p-6 rounded-lg">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Room Rate (per night):</span>
                        <span class="font-semibold">{{ $bookingHistory->currency }}{{ number_format($bookingHistory->room_rate, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal ({{ $bookingHistory->number_of_nights }} nights):</span>
                        <span class="font-semibold">{{ $bookingHistory->currency }}{{ number_format($bookingHistory->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax (10%):</span>
                        <span class="font-semibold">{{ $bookingHistory->currency }}{{ number_format($bookingHistory->tax, 2) }}</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between">
                        <span class="text-gray-600">Original Total:</span>
                        <span class="font-semibold">{{ $bookingHistory->currency }}{{ number_format($bookingHistory->total_amount, 2) }}</span>
                    </div>
                    @if($bookingHistory->final_amount && $bookingHistory->final_amount != $bookingHistory->total_amount)
                    <div class="border-t pt-3 flex justify-between bg-yellow-50 p-3 rounded">
                        <span class="text-gray-600 font-semibold">Final Amount (Updated):</span>
                        <span class="font-bold text-lg">{{ $bookingHistory->currency }}{{ number_format($bookingHistory->final_amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="border-t pt-3 flex justify-between">
                        <span class="text-gray-600">Payments Received:</span>
                        <span class="font-semibold text-green-600">{{ $bookingHistory->currency }}{{ number_format($bookingHistory->payments_received, 2) }}</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between text-lg font-bold">
                        <span>Balance:</span>
                        <span class="@if($bookingHistory->balance > 0) text-red-600 @else text-green-600 @endif">
                            {{ $bookingHistory->currency }}{{ number_format($bookingHistory->balance, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        @if($bookingHistory->special_requests)
        <hr class="my-6">
        <h3 class="text-lg font-bold mb-4">Special Requests</h3>
        <p class="text-gray-700 bg-gray-50 p-4 rounded">{{ $bookingHistory->special_requests }}</p>
        @endif

        <!-- Actions -->
        <div class="flex gap-2 mt-6">
            <a href="{{ route('booking-history.index', $property) }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 text-center font-semibold">
                Back to History
            </a>
            <a href="{{ route('booking-history.export', [$property, $bookingHistory]) }}" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center font-semibold">
                Export Receipt
            </a>
        </div>
    </div>
</div>
</x-app-layout>

