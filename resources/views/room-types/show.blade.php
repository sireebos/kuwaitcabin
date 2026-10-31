<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Room Type Details') }}
        </h2>
    </x-slot>

<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">{{ $roomType->name }}</h1>
            <p class="text-gray-600">{{ $roomType->description }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('properties.room-types.edit', [$property, $roomType]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Edit
            </a>
            <a href="{{ route('properties.room-types.index', $property) }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                Back
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="mb-6">
        <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $roomType->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $roomType->is_active ? 'Active' : 'Inactive' }}
        </span>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-600 text-sm font-medium">Capacity</div>
            <div class="text-3xl font-bold text-blue-600">{{ $roomType->capacity }}</div>
            <div class="text-xs text-gray-500">guests</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-600 text-sm font-medium">Beds</div>
            <div class="text-3xl font-bold text-green-600">{{ $roomType->beds }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-600 text-sm font-medium">Base Price</div>
            <div class="text-3xl font-bold text-purple-600">₦{{ number_format($roomType->base_price, 2) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-600 text-sm font-medium">Total Rooms</div>
            <div class="text-3xl font-bold text-orange-600">{{ $roomType->total_rooms }}</div>
        </div>
    </div>

    <!-- Room Type Information -->
    <div class="grid grid-cols-2 gap-6 mb-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-600 text-sm">Room Type Name</span>
                    <p class="font-medium">{{ $roomType->name }}</p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Slug</span>
                    <p class="font-medium">{{ $roomType->slug }}</p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Property</span>
                    <p class="font-medium">
                        <a href="{{ route('properties.show', $property) }}" class="text-blue-600 hover:underline">{{ $property->name }}</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Pricing Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Pricing Information</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-600 text-sm">Base Price</span>
                    <p class="font-medium">{{ $property->currency }} {{ number_format($roomType->base_price, 2) }}</p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Total Rooms</span>
                    <p class="font-medium">{{ $roomType->total_rooms }}</p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Rate Plans</span>
                    <p class="font-medium">{{ $roomType->ratePlans->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Details -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Room Details</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <span class="text-gray-600 text-sm">Capacity</span>
                <p class="font-medium">{{ $roomType->capacity }} guests</p>
            </div>
            <div>
                <span class="text-gray-600 text-sm">Number of Beds</span>
                <p class="font-medium">{{ $roomType->beds }}</p>
            </div>
        </div>
    </div>

    <!-- Amenities -->
    @if($roomType->amenities && count($roomType->amenities) > 0)
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Amenities</h3>
            <div class="grid grid-cols-3 gap-3">
                @foreach($roomType->amenities as $amenity)
                    <div class="flex items-center">
                        <span class="text-green-600 mr-2">✓</span>
                        <span>{{ $amenity }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Timestamps -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Timestamps</h3>
        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
            <div>
                <span>Created:</span>
                <p>{{ $roomType->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div>
                <span>Last Updated:</span>
                <p>{{ $roomType->updated_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>

</x-app-layout>

