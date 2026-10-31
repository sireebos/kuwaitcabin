<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Restaurant Tables') }}
        </h2>
    </x-slot>

<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold">Restaurant Tables</h1>
        <div class="flex gap-2">
            <a href="{{ route('properties.restaurant-tables.layout', $property) }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                📐 Layout View
            </a>
            <a href="{{ route('properties.restaurant-tables.create', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Add Table
            </a>
        </div>
    </div>

    @if ($tables->count())
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Table #</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Capacity</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Section</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Notes</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($tables as $table)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold">{{ $table->table_number }}</td>
                            <td class="px-6 py-4">{{ $table->capacity }} seats</td>
                            <td class="px-6 py-4">{{ $table->section ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded text-sm font-semibold
                                    @if($table->status === 'available') bg-green-100 text-green-800
                                    @elseif($table->status === 'occupied') bg-red-100 text-red-800
                                    @elseif($table->status === 'reserved') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($table->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-sm">{{ Str::limit($table->notes, 30) }}</td>
                            <td class="px-6 py-4 space-x-2">
                                <a href="{{ route('properties.restaurant-tables.edit', [$property, $table]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                    Edit
                                </a>
                                <form action="{{ route('properties.restaurant-tables.destroy', [$property, $table]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this table?')">
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
            {{ $tables->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-500 mb-4">No tables created yet</p>
            <a href="{{ route('properties.restaurant-tables.create', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create First Table
            </a>
        </div>
    @endif
</div>
</x-app-layout>

