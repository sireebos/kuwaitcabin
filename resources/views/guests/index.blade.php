<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Guest Management') }}
        </h2>
    </x-slot>

<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Guests</h1>
    <a href="{{ route('properties.guests.create', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Register Guest
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Phone</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Address</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Bookings</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($guests as $guest)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $guest->full_name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $guest->email }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $guest->phone }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $guest->address ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $guest->bookings->count() }}</td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('properties.guests.show', [$property, $guest]) }}" class="text-blue-600 hover:text-blue-800 mr-3">View</a>
                        <a href="{{ route('properties.guests.edit', [$property, $guest]) }}" class="text-green-600 hover:text-green-800 mr-3">Edit</a>
                        <form method="POST" action="{{ route('properties.guests.destroy', [$property, $guest]) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No guests found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $guests->links() }}
</div>

</x-app-layout>

