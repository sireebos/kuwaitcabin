<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reservation Details') }}
        </h2>
    </x-slot>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold">{{ $reservation->guest_name }}</h1>
                <p class="text-gray-600 mt-1">{{ $reservation->guest_phone }}</p>
            </div>
            <span class="px-4 py-2 rounded text-sm font-semibold
                @if($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($reservation->status === 'confirmed') bg-blue-100 text-blue-800
                @elseif($reservation->status === 'seated') bg-green-100 text-green-800
                @elseif($reservation->status === 'completed') bg-gray-100 text-gray-800
                @else bg-red-100 text-red-800
                @endif">
                {{ ucfirst($reservation->status) }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Table</h3>
                <p class="text-lg font-semibold">Table {{ $reservation->table->table_number }}</p>
                <p class="text-gray-600 text-sm">{{ $reservation->table->capacity }} seats</p>
            </div>
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Reservation Time</h3>
                <p class="text-lg font-semibold">{{ $reservation->reservation_time->format('M d, Y') }}</p>
                <p class="text-gray-600 text-sm">{{ $reservation->reservation_time->format('H:i') }}</p>
            </div>
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Number of Guests</h3>
                <p class="text-lg font-semibold">{{ $reservation->number_of_guests }}</p>
            </div>
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Booked On</h3>
                <p class="text-lg font-semibold">{{ $reservation->created_at->format('M d, Y') }}</p>
                <p class="text-gray-600 text-sm">{{ $reservation->created_at->format('H:i') }}</p>
            </div>
        </div>

        @if ($reservation->special_requests)
            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Special Requests</h3>
                <p class="text-gray-800">{{ $reservation->special_requests }}</p>
            </div>
        @endif

        @if ($reservation->guest)
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Guest Profile</h3>
                <p class="text-gray-800">{{ $reservation->guest->fullName }}</p>
                <p class="text-gray-600 text-sm">{{ $reservation->guest->email }}</p>
            </div>
        @endif

        <hr class="my-6">

        <div class="flex gap-4">
            <a href="{{ route('properties.restaurant-reservations.edit', [$property, $reservation]) }}" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center font-semibold">
                Edit Reservation
            </a>
            <form action="{{ route('properties.restaurant-reservations.destroy', [$property, $reservation]) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete this reservation?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 font-semibold">
                    Delete Reservation
                </button>
            </form>
            <a href="{{ route('properties.restaurant-reservations.index', $property) }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 text-center font-semibold">
                Back to List
            </a>
        </div>
    </div>
</div>
</x-app-layout>

