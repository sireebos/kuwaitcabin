<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Property Details') }}
        </h2>
    </x-slot>

<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">{{ $property->name }}</h1>
            <p class="text-gray-600">{{ $property->address }}, {{ $property->city }}, {{ $property->state }} {{ $property->postal_code }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('properties.edit', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Edit
            </a>
            <a href="{{ route('properties.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                Back
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="mb-6">
        <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $property->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $property->is_active ? 'Active' : 'Inactive' }}
        </span>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-600 text-sm font-medium">Total Rooms</div>
            <div class="text-3xl font-bold text-blue-600">{{ $stats['total_rooms'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-600 text-sm font-medium">Room Types</div>
            <div class="text-3xl font-bold text-green-600">{{ $stats['room_types'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-600 text-sm font-medium">Total Bookings</div>
            <div class="text-3xl font-bold text-purple-600">{{ $stats['total_bookings'] }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-600 text-sm font-medium">Total Guests</div>
            <div class="text-3xl font-bold text-orange-600">{{ $stats['total_guests'] }}</div>
        </div>
    </div>

    <!-- Property Information -->
    <div class="grid grid-cols-2 gap-6 mb-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-600 text-sm">Property Name</span>
                    <p class="font-medium">{{ $property->name }}</p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Slug</span>
                    <p class="font-medium">{{ $property->slug }}</p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Description</span>
                    <p class="font-medium">{{ $property->description ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Total Rooms</span>
                    <p class="font-medium">{{ $property->total_rooms }}</p>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-600 text-sm">Email</span>
                    <p class="font-medium"><a href="mailto:{{ $property->email }}" class="text-blue-600 hover:underline">{{ $property->email }}</a></p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Phone</span>
                    <p class="font-medium"><a href="tel:{{ $property->phone }}" class="text-blue-600 hover:underline">{{ $property->phone }}</a></p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Website</span>
                    <p class="font-medium">
                        @if($property->website)
                            <a href="{{ $property->website }}" target="_blank" class="text-blue-600 hover:underline">{{ $property->website }}</a>
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Currency</span>
                    <p class="font-medium">{{ $property->currency }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Address Information -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Address Information</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <span class="text-gray-600 text-sm">Address</span>
                <p class="font-medium">{{ $property->address }}</p>
            </div>
            <div>
                <span class="text-gray-600 text-sm">City</span>
                <p class="font-medium">{{ $property->city }}</p>
            </div>
            <div>
                <span class="text-gray-600 text-sm">State</span>
                <p class="font-medium">{{ $property->state }}</p>
            </div>
            <div>
                <span class="text-gray-600 text-sm">Postal Code</span>
                <p class="font-medium">{{ $property->postal_code }}</p>
            </div>
            <div>
                <span class="text-gray-600 text-sm">Country</span>
                <p class="font-medium">{{ $property->country }}</p>
            </div>
        </div>
    </div>

    <!-- Operating Hours -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Operating Hours</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <span class="text-gray-600 text-sm">Check-in Time</span>
                <p class="font-medium">{{ sprintf('%02d:%02d', intval($property->check_in_time), intval(($property->check_in_time % 1) * 60)) }}</p>
            </div>
            <div>
                <span class="text-gray-600 text-sm">Check-out Time</span>
                <p class="font-medium">{{ sprintf('%02d:%02d', intval($property->check_out_time), intval(($property->check_out_time % 1) * 60)) }}</p>
            </div>
        </div>
    </div>

    <!-- Timestamps -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Timestamps</h3>
        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
            <div>
                <span>Created:</span>
                <p>{{ $property->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div>
                <span>Last Updated:</span>
                <p>{{ $property->updated_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>

</x-app-layout>

