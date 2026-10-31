<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking History') }}
        </h2>
    </x-slot>

<div class="mb-6">
    <h1 class="text-2xl font-bold">Completed Bookings History</h1>
    <p class="text-gray-600 mt-2">View all checked-out guests and their booking details</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold">Guest Name</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Room</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Check-in</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Check-out</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Nights</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Final Amount</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Paid</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Balance</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Checked Out</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($history as $booking)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-semibold">{{ $booking->guest->full_name }}</td>
                    <td class="px-6 py-4 text-sm">{{ $booking->room->room_number ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $booking->check_in_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm">{{ $booking->check_out_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm text-center">{{ $booking->number_of_nights }}</td>
                    <td class="px-6 py-4 text-sm font-semibold">
                        {{ $booking->currency }}{{ number_format($booking->final_amount ?? $booking->total_amount, 2) }}
                    </td>
                    <td class="px-6 py-4 text-sm text-green-600 font-semibold">
                        {{ $booking->currency }}{{ number_format($booking->payments_received, 2) }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="@if($booking->balance > 0) text-red-600 @else text-green-600 @endif font-semibold">
                            {{ $booking->currency }}{{ number_format($booking->balance, 2) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $booking->checked_out_at->format('M d, Y H:i') }}</td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('booking-history.show', [$property, $booking]) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                            View Details
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                        No booking history available
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $history->links() }}
</div>
</x-app-layout>

