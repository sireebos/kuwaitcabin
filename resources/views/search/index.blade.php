<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search Available Rooms') }}
        </h2>
    </x-slot>

<div class="max-w-6xl mx-auto">
    <!-- Search Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold mb-2">Find Your Perfect Room</h1>
        <p class="text-gray-600">Search for available rooms across all our properties</p>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow p-8 mb-8">
        <form method="POST" action="{{ route('search.search') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Property Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Property (Optional)</label>
                    <select name="property_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Properties</option>
                        @foreach($properties as $property)
                            <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>
                                {{ $property->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Check-in Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Check-in *</label>
                    <input type="date" name="check_in" value="{{ old('check_in', now()->format('Y-m-d')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('check_in') border-red-500 @enderror">
                    @error('check_in') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Check-out Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Check-out *</label>
                    <input type="date" name="check_out" value="{{ old('check_out', now()->addDays(1)->format('Y-m-d')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('check_out') border-red-500 @enderror">
                    @error('check_out') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Number of Guests -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Guests *</label>
                    <input type="number" name="guests" value="{{ old('guests', 1) }}" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('guests') border-red-500 @enderror">
                    @error('guests') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Room Type (Optional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Room Type (Optional)</label>
                    <select name="room_type_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Any Type</option>
                        @foreach($properties as $property)
                            @foreach($property->roomTypes()->where('is_active', true)->get() as $roomType)
                                <option value="{{ $roomType->id }}" {{ old('room_type_id') == $roomType->id ? 'selected' : '' }}>
                                    {{ $roomType->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Search Button -->
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                    Search Rooms
                </button>
                <button type="reset" class="bg-gray-300 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-400 font-semibold">
                    Clear
                </button>
            </div>
        </form>
    </div>

    <!-- Info Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">Easy Search</h3>
            <p class="text-blue-800">Find available rooms by selecting your dates and number of guests.</p>
        </div>
        <div class="bg-green-50 rounded-lg p-6 border border-green-200">
            <h3 class="text-lg font-semibold text-green-900 mb-2">Best Prices</h3>
            <p class="text-green-800">Compare prices across different room types and properties.</p>
        </div>
        <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
            <h3 class="text-lg font-semibold text-purple-900 mb-2">Instant Booking</h3>
            <p class="text-purple-800">Book your room instantly with our secure booking system.</p>
        </div>
    </div>
</div>

</x-app-layout>

