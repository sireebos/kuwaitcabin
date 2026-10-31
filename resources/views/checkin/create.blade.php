<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Check-in Guest') }}
        </h2>
    </x-slot>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Check-in Guest</h2>

        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 text-sm">Guest Name</p>
                    <p class="font-semibold">{{ $booking->guest->full_name }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Booking Reference</p>
                    <p class="font-semibold">{{ $booking->booking_reference }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Check-in Date</p>
                    <p class="font-semibold">{{ $booking->check_in_date }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Number of Guests</p>
                    <p class="font-semibold">{{ $booking->number_of_guests }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Booked Room</p>
                    <p class="font-semibold">{{ $booking->room->room_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Room Rate</p>
                    <p class="font-semibold">₦{{ number_format($booking->room_rate ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('checkin.store', [$property, $booking]) }}">
            @csrf

            <div class="mb-4">
                <label for="room_id" class="block text-sm font-semibold text-gray-700 mb-2">Select Room *</label>
                <select id="room_id" name="room_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Choose a room...</option>
                    @foreach ($availableRooms as $room)
                        <option value="{{ $room->id }}" @if($room->id === $bookedRoomId) selected @endif>
                            {{ $room->room_number }} - {{ $room->room_type }} ({{ $room->capacity }} guests)
                        </option>
                    @endforeach
                </select>
                @error('room_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    Check-in Guest
                </button>
                <a href="{{ route('properties.bookings.show', [$property, $booking]) }}" class="flex-1 bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>

