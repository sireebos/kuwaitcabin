<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Guest Details') }}
        </h2>
    </x-slot>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $guest->full_name }}</h1>
                <p class="text-gray-600 mt-2">Guest Profile</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('properties.guests.edit', [$property, $guest]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Edit
                </a>
                <form method="POST" action="{{ route('properties.guests.destroy', [$property, $guest]) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" onclick="return confirm('Are you sure?')">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Email</h3>
                <p class="text-lg text-gray-900">{{ $guest->email }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Phone</h3>
                <p class="text-lg text-gray-900">{{ $guest->phone }}</p>
            </div>
            @if($guest->address)
                <div class="col-span-2">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Address</h3>
                    <p class="text-lg text-gray-900">{{ $guest->address }}</p>
                </div>
            @endif
        </div>
    </div>

    @if($guest->bookings->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Bookings ({{ $guest->bookings->count() }})</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Room</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Check-in</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Check-out</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($guest->bookings as $booking)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->room->room_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->check_in_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->check_out_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($booking->status === 'checked_in') bg-blue-100 text-blue-800
                                        @elseif($booking->status === 'checked_out') bg-gray-100 text-gray-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($booking->total_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-center">No bookings found for this guest</p>
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('properties.guests.index', $property) }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Guests
        </a>
    </div>
</div>

</x-app-layout>

