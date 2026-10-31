<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Property Management') }}
        </h2>
    </x-slot>

<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Properties</h1>
    <a href="{{ route('properties.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Add Property
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">City</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Rooms</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($properties as $property)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $property->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $property->city }}, {{ $property->state }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $property->email }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $property->total_rooms }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $property->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $property->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('properties.show', $property) }}" class="text-blue-600 hover:text-blue-800 mr-3">View</a>
                        <a href="{{ route('properties.edit', $property) }}" class="text-green-600 hover:text-green-800 mr-3">Edit</a>
                        <form method="POST" action="{{ route('properties.destroy', $property) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No properties found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $properties->links() }}
</div>

</x-app-layout>

