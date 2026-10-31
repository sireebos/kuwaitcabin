<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Room Type') }}
        </h2>
    </x-slot>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Room Type: {{ $roomType->name }}</h1>

        <form method="POST" action="{{ route('properties.room-types.update', [$property, $roomType]) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Basic Information -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Room Type Name *</label>
                        <input type="text" name="name" value="{{ old('name', $roomType->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
                        <input type="text" name="slug" value="{{ old('slug', $roomType->slug) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('slug') border-red-500 @enderror">
                        @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $roomType->description) }}</textarea>
                </div>
            </div>

            <!-- Room Details -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold mb-4">Room Details</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Capacity (Guests) *</label>
                        <input type="number" name="capacity" value="{{ old('capacity', $roomType->capacity) }}" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('capacity') border-red-500 @enderror">
                        @error('capacity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Number of Beds *</label>
                        <input type="number" name="beds" value="{{ old('beds', $roomType->beds) }}" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('beds') border-red-500 @enderror">
                        @error('beds') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Base Price (₦) *</label>
                        <input type="number" name="base_price" value="{{ old('base_price', $roomType->base_price) }}" required min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('base_price') border-red-500 @enderror">
                        @error('base_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Rooms of This Type *</label>
                        <input type="number" name="total_rooms" value="{{ old('total_rooms', $roomType->total_rooms) }}" required min="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('total_rooms') border-red-500 @enderror">
                        @error('total_rooms') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Amenities -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold mb-4">Amenities</h3>
                
                <div class="grid grid-cols-3 gap-3">
                    @foreach($amenitiesList as $amenity)
                        <label class="flex items-center">
                            <input type="checkbox" name="amenities[]" value="{{ $amenity }}" {{ in_array($amenity, old('amenities', $roomType->amenities ?? [])) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">{{ $amenity }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Status -->
            <div class="pb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $roomType->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Update Room Type
                </button>
                <a href="{{ route('properties.room-types.show', [$property, $roomType]) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

</x-app-layout>

