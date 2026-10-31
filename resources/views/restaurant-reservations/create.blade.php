<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Reservation') }}
        </h2>
    </x-slot>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Create New Reservation</h1>

        <form action="{{ route('properties.restaurant-reservations.store', $property) }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="guest_name" class="block text-sm font-semibold text-gray-700 mb-2">Guest Name *</label>
                <input type="text" id="guest_name" name="guest_name" value="{{ old('guest_name') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Full name">
                @error('guest_name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="guest_phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number *</label>
                <input type="tel" id="guest_phone" name="guest_phone" value="{{ old('guest_phone') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="+1-234-567-8900">
                @error('guest_phone')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="guest_id" class="block text-sm font-semibold text-gray-700 mb-2">Existing Guest (Optional)</label>
                <select id="guest_id" name="guest_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">-- Select a guest --</option>
                    @foreach ($guests as $guest)
                        <option value="{{ $guest->id }}" {{ old('guest_id') == $guest->id ? 'selected' : '' }}>
                            {{ $guest->fullName }} ({{ $guest->phone }})
                        </option>
                    @endforeach
                </select>
                @error('guest_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="restaurant_table_id" class="block text-sm font-semibold text-gray-700 mb-2">Table *</label>
                <select id="restaurant_table_id" name="restaurant_table_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">-- Select a table --</option>
                    @foreach ($tables as $table)
                        <option value="{{ $table->id }}" {{ old('restaurant_table_id') == $table->id ? 'selected' : '' }}>
                            Table {{ $table->table_number }} ({{ $table->capacity }} seats)
                        </option>
                    @endforeach
                </select>
                @error('restaurant_table_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="reservation_time" class="block text-sm font-semibold text-gray-700 mb-2">Reservation Date & Time *</label>
                <input type="datetime-local" id="reservation_time" name="reservation_time" value="{{ old('reservation_time') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('reservation_time')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="number_of_guests" class="block text-sm font-semibold text-gray-700 mb-2">Number of Guests *</label>
                <input type="number" id="number_of_guests" name="number_of_guests" value="{{ old('number_of_guests', 1) }}" min="1" max="20" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('number_of_guests')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="special_requests" class="block text-sm font-semibold text-gray-700 mb-2">Special Requests</label>
                <textarea id="special_requests" name="special_requests" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., High chair needed, window seat preferred">{{ old('special_requests') }}</textarea>
                @error('special_requests')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-semibold">
                    Create Reservation
                </button>
                <a href="{{ route('properties.restaurant-reservations.index', $property) }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 text-center font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>

