<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Restaurant Reservations') }}
        </h2>
    </x-slot>

<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold">Reservations</h1>
        <a href="{{ route('properties.restaurant-reservations.create', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + New Reservation
        </a>
    </div>

    @if ($reservations->count())
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Guest Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Phone</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Table</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date & Time</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Guests</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($reservations as $reservation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold">{{ $reservation->guest_name }}</td>
                            <td class="px-6 py-4">{{ $reservation->guest_phone }}</td>
                            <td class="px-6 py-4">Table {{ $reservation->table->table_number }}</td>
                            <td class="px-6 py-4">{{ $reservation->reservation_time->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4">{{ $reservation->number_of_guests }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded text-sm font-semibold
                                    @if($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($reservation->status === 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($reservation->status === 'seated') bg-green-100 text-green-800
                                    @elseif($reservation->status === 'completed') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                <a href="{{ route('properties.restaurant-reservations.show', [$property, $reservation]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                    View
                                </a>
                                <a href="{{ route('properties.restaurant-reservations.edit', [$property, $reservation]) }}" class="text-gray-600 hover:text-gray-800 text-sm font-semibold">
                                    Edit
                                </a>
                                <form action="{{ route('properties.restaurant-reservations.destroy', [$property, $reservation]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this reservation?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $reservations->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-500 mb-4">No reservations yet</p>
            <a href="{{ route('properties.restaurant-reservations.create', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create First Reservation
            </a>
        </div>
    @endif
</div>
</x-app-layout>

