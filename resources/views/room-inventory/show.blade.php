<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Room Inventory Details') }}
        </h2>
    </x-slot>

<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">Room {{ $roomInventory->room->room_number }} - {{ $roomInventory->date->format('M d, Y') }}</h1>
            <p class="text-gray-600">{{ $roomInventory->room->roomType->name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('properties.room-inventory.edit', [$property, $roomInventory]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Edit
            </a>
            <a href="{{ route('properties.room-inventory.index', $property) }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                Back
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="mb-6">
        @php
            $statusColors = [
                'available' => 'bg-green-100 text-green-800',
                'occupied' => 'bg-red-100 text-red-800',
                'maintenance' => 'bg-yellow-100 text-yellow-800',
                'blocked' => 'bg-gray-100 text-gray-800',
            ];
        @endphp
        <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $statusColors[$roomInventory->status] ?? 'bg-gray-100' }}">
            {{ ucfirst($roomInventory->status) }}
        </span>
    </div>

    <!-- Inventory Information -->
    <div class="grid grid-cols-2 gap-6 mb-6">
        <!-- Room Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Room Information</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-600 text-sm">Room Number</span>
                    <p class="font-medium">{{ $roomInventory->room->room_number }}</p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Room Type</span>
                    <p class="font-medium">
                        <a href="{{ route('properties.room-types.show', [$property, $roomInventory->room->roomType]) }}" class="text-blue-600 hover:underline">
                            {{ $roomInventory->room->roomType->name }}
                        </a>
                    </p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Property</span>
                    <p class="font-medium">
                        <a href="{{ route('properties.show', $property) }}" class="text-blue-600 hover:underline">{{ $property->name }}</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Inventory Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Inventory Information</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-600 text-sm">Date</span>
                    <p class="font-medium">{{ $roomInventory->date->format('M d, Y (l)') }}</p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Status</span>
                    <p class="font-medium">{{ ucfirst($roomInventory->status) }}</p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Price</span>
                    <p class="font-medium">{{ $property->currency }} {{ number_format($roomInventory->price, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Details -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Status Details</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <span class="text-gray-600 text-sm">Current Status</span>
                <p class="font-medium">
                    @if($roomInventory->status === 'available')
                        <span class="text-green-600">✓ Available for booking</span>
                    @elseif($roomInventory->status === 'occupied')
                        <span class="text-red-600">✗ Room is occupied</span>
                    @elseif($roomInventory->status === 'maintenance')
                        <span class="text-yellow-600">⚠ Under maintenance</span>
                    @else
                        <span class="text-gray-600">⊘ Room is blocked</span>
                    @endif
                </p>
            </div>
            <div>
                <span class="text-gray-600 text-sm">Price for this date</span>
                <p class="font-medium text-lg">{{ $property->currency }} {{ number_format($roomInventory->price, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Timestamps -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Timestamps</h3>
        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
            <div>
                <span>Created:</span>
                <p>{{ $roomInventory->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div>
                <span>Last Updated:</span>
                <p>{{ $roomInventory->updated_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>

</x-app-layout>

