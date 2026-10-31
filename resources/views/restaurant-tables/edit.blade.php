<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Restaurant Table') }}
        </h2>
    </x-slot>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Table {{ $restaurantTable->table_number }}</h1>

        <form action="{{ route('properties.restaurant-tables.update', [$property, $restaurantTable]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="table_number" class="block text-sm font-semibold text-gray-700 mb-2">Table Number *</label>
                <input type="text" id="table_number" name="table_number" value="{{ old('table_number', $restaurantTable->table_number) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('table_number')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="capacity" class="block text-sm font-semibold text-gray-700 mb-2">Capacity (Seats) *</label>
                <input type="number" id="capacity" name="capacity" value="{{ old('capacity', $restaurantTable->capacity) }}" min="1" max="20" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('capacity')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="section" class="block text-sm font-semibold text-gray-700 mb-2">Section</label>
                <input type="text" id="section" name="section" value="{{ old('section', $restaurantTable->section) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Main Hall, Patio, VIP">
                @error('section')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status *</label>
                <select id="status" name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="available" {{ old('status', $restaurantTable->status) === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ old('status', $restaurantTable->status) === 'occupied' ? 'selected' : '' }}>Occupied</option>
                    <option value="reserved" {{ old('status', $restaurantTable->status) === 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="maintenance" {{ old('status', $restaurantTable->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
                @error('status')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Near window, corner table">{{ old('notes', $restaurantTable->notes) }}</textarea>
                @error('notes')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-semibold">
                    Update Table
                </button>
                <a href="{{ route('properties.restaurant-tables.index', $property) }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 text-center font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>

