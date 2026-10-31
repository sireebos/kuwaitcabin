<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search Results') }}
        </h2>
    </x-slot>

<div class="max-w-6xl mx-auto">
    <!-- Search Summary -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Available Rooms</h1>
        <p class="text-gray-600">
            {{ $checkIn->format('M d, Y') }} to {{ $checkOut->format('M d, Y') }} • 
            {{ $checkOut->diffInDays($checkIn) }} night(s) • 
            {{ $guests }} guest(s)
        </p>
    </div>

    <!-- Back to Search -->
    <div class="mb-6">
        <a href="{{ route('search.index') }}" class="text-blue-600 hover:text-blue-800">← Back to Search</a>
    </div>

    @if(count($availableRooms) > 0)
        <!-- Available Rooms Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($availableRooms as $item)
                @php
                    $roomType = $item['room_type'];
                    $property = $roomType->property;
                @endphp
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                    <!-- Room Type Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4">
                        <h3 class="text-xl font-bold">{{ $roomType->name }}</h3>
                        <p class="text-blue-100">{{ $property->name }}</p>
                    </div>

                    <!-- Room Details -->
                    <div class="p-6 space-y-4">
                        <!-- Description -->
                        @if($roomType->description)
                            <p class="text-gray-600 text-sm">{{ $roomType->description }}</p>
                        @endif

                        <!-- Room Info -->
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Capacity</span>
                                <p class="font-semibold">{{ $roomType->capacity }} guests</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Beds</span>
                                <p class="font-semibold">{{ $roomType->beds }}</p>
                            </div>
                        </div>

                        <!-- Amenities -->
                        @if($roomType->amenities && count($roomType->amenities) > 0)
                            <div>
                                <span class="text-gray-600 text-sm">Amenities</span>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach(array_slice($roomType->amenities, 0, 3) as $amenity)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">{{ $amenity }}</span>
                                    @endforeach
                                    @if(count($roomType->amenities) > 3)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">+{{ count($roomType->amenities) - 3 }} more</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Availability -->
                        <div class="bg-green-50 border border-green-200 rounded p-3">
                            <p class="text-sm text-green-800">
                                <strong>{{ $item['available_count'] }}</strong> room(s) available
                            </p>
                        </div>

                        <!-- Pricing -->
                        <div class="border-t pt-4">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <p class="text-gray-600 text-sm">Price per night</p>
                                    <p class="text-2xl font-bold text-blue-600">₦{{ number_format($roomType->base_price, 2) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-gray-600 text-sm">Total ({{ $item['nights'] }} nights)</p>
                                    <p class="text-2xl font-bold text-green-600">₦{{ number_format($item['total_price'], 2) }}</p>
                                </div>
                            </div>

                            <!-- Book Button -->
                            <a href="{{ route('booking.public-create', ['roomType' => $roomType->id, 'check_in' => $checkIn->format('Y-m-d'), 'check_out' => $checkOut->format('Y-m-d'), 'guests' => $guests]) }}" class="block w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 text-center font-semibold">
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- No Results -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
            <h3 class="text-xl font-semibold text-yellow-900 mb-2">No Rooms Available</h3>
            <p class="text-yellow-800 mb-4">
                Sorry, we couldn't find any available rooms for your selected dates and criteria.
            </p>
            <a href="{{ route('search.index') }}" class="inline-block bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700">
                Try Different Dates
            </a>
        </div>
    @endif
</div>

</x-app-layout>

