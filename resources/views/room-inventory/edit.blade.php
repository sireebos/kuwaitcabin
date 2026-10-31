<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Room Inventory') }}
        </h2>
    </x-slot>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Room Inventory</h1>

        <form method="POST" action="{{ route('properties.room-inventory.update', [$property, $roomInventory]) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Room Information -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold mb-4">Room Information</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Room *</label>
                        <select name="room_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('room_id') border-red-500 @enderror">
                            <option value="">Select a room</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ old('room_id', $roomInventory->room_id) == $room->id ? 'selected' : '' }}>
                                    Room {{ $room->room_number }} - {{ $room->roomType->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('room_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date *</label>
                        <input type="date" name="date" value="{{ old('date', $roomInventory->date->format('Y-m-d')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date') border-red-500 @enderror">
                        @error('date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Status and Price -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold mb-4">Status and Pricing</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror">
                            <option value="">Select status</option>
                            <option value="available" {{ old('status', $roomInventory->status) == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="occupied" {{ old('status', $roomInventory->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="maintenance" {{ old('status', $roomInventory->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="blocked" {{ old('status', $roomInventory->status) == 'blocked' ? 'selected' : '' }}>Blocked</option>
                        </select>
                        @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price ({{ $property->currency }}) *</label>
                        <input type="number" name="price" value="{{ old('price', $roomInventory->price) }}" required min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror">
                        @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    <strong>Current Status:</strong> <span class="font-semibold">{{ ucfirst($roomInventory->status) }}</span>
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Update Inventory
                </button>
                <a href="{{ route('properties.room-inventory.show', [$property, $roomInventory]) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

</x-app-layout>

