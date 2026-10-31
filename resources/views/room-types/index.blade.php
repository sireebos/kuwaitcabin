<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Room Types Management') }}
        </h2>
    </x-slot>

<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Room Types - {{ $property->name }}</h1>
    <a href="{{ route('properties.room-types.create', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Add Room Type
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Capacity</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Beds</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Base Price</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Total Rooms</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($roomTypes as $roomType)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $roomType->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $roomType->capacity }} guests</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $roomType->beds }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">₦{{ number_format($roomType->base_price, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $roomType->total_rooms }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $roomType->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $roomType->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('properties.room-types.show', [$property, $roomType]) }}" class="text-blue-600 hover:text-blue-800 mr-3">View</a>
                        <a href="{{ route('properties.room-types.edit', [$property, $roomType]) }}" class="text-green-600 hover:text-green-800 mr-3">Edit</a>
                        <form method="POST" action="{{ route('properties.room-types.destroy', [$property, $roomType]) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No room types found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $roomTypes->links() }}
</div>

</x-app-layout>

