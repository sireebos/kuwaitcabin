<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Room</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-8">
            <div class="max-w-6xl mx-auto px-4">
                <h1 class="text-3xl font-bold">Complete Your Booking</h1>
                <p class="text-blue-100 mt-2">{{ $roomType->name }} at {{ $roomType->property->name }}</p>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Booking Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow p-8">
                        <form method="POST" action="{{ route('booking.public-store') }}" class="space-y-6">
                            @csrf

                            <!-- Room Selection -->
                            <div>
                                <h2 class="text-xl font-bold mb-4">Select Your Room</h2>
                                @if($availableRooms->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($availableRooms as $room)
                                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition" onclick="selectRoom({{ $room->id }}, {{ $roomType->base_price }})">
                                                <input type="radio" name="room_id" value="{{ $room->id }}" class="w-4 h-4 text-blue-600" required>
                                                <div class="ml-4 flex-1">
                                                    <p class="font-semibold">Room {{ $room->room_number }}</p>
                                                    <p class="text-sm text-gray-600">Floor {{ $room->floor }}, {{ $room->wing }} Wing</p>
                                                </div>
                                                <p class="font-bold text-blue-600">₦{{ number_format($roomType->base_price, 2) }}/night</p>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-yellow-800">
                                        No rooms available for the selected dates.
                                    </div>
                                @endif
                            </div>

                            <!-- Hidden Fields -->
                            <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                            <input type="hidden" name="check_in_date" value="{{ $checkIn->format('Y-m-d') }}">
                            <input type="hidden" name="check_out_date" value="{{ $checkOut->format('Y-m-d') }}">
                            <input type="hidden" name="number_of_guests" value="{{ $guests }}">
                            <input type="hidden" name="room_rate" id="room_rate" value="{{ $roomType->base_price }}">

                            <!-- Guest Information -->
                            <div>
                                <h2 class="text-xl font-bold mb-4">Guest Information</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                                        <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror">
                                        @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_name') border-red-500 @enderror">
                                        @error('last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone *</label>
                                        <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                                        @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Special Requests -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Special Requests</label>
                                <textarea name="special_requests" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Any special requests or requirements?">{{ old('special_requests') }}</textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex gap-4">
                                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-semibold">
                                    Complete Booking
                                </button>
                                <a href="{{ route('search.index') }}" class="flex-1 bg-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-400 font-semibold text-center">
                                    Back to Search
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Booking Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-6 sticky top-8">
                        <h2 class="text-xl font-bold mb-4">Booking Summary</h2>

                        <!-- Room Type Info -->
                        <div class="mb-6 pb-6 border-b">
                            <p class="text-gray-600 text-sm">Room Type</p>
                            <p class="font-semibold">{{ $roomType->name }}</p>
                            @if($roomType->description)
                                <p class="text-sm text-gray-600 mt-2">{{ $roomType->description }}</p>
                            @endif
                        </div>

                        <!-- Dates -->
                        <div class="mb-6 pb-6 border-b">
                            <p class="text-gray-600 text-sm">Check-in</p>
                            <p class="font-semibold">{{ $checkIn->format('M d, Y') }}</p>
                            <p class="text-gray-600 text-sm mt-3">Check-out</p>
                            <p class="font-semibold">{{ $checkOut->format('M d, Y') }}</p>
                        </div>

                        <!-- Guests -->
                        <div class="mb-6 pb-6 border-b">
                            <p class="text-gray-600 text-sm">Number of Guests</p>
                            <p class="font-semibold">{{ $guests }} guest(s)</p>
                        </div>

                        <!-- Pricing -->
                        <div class="mb-6 pb-6 border-b">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">{{ $checkOut->diffInDays($checkIn) }} nights × ₦{{ number_format($roomType->base_price, 2) }}</span>
                                <span class="font-semibold" id="subtotal">₦{{ number_format($roomType->base_price * $checkOut->diffInDays($checkIn), 2) }}</span>
                            </div>
                            <div class="flex justify-between mb-4">
                                <span class="text-gray-600">Tax (10%)</span>
                                <span class="font-semibold" id="tax">₦{{ number_format($roomType->base_price * $checkOut->diffInDays($checkIn) * 0.1, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-lg">
                                <span class="font-bold">Total</span>
                                <span class="font-bold text-blue-600" id="total">₦{{ number_format($roomType->base_price * $checkOut->diffInDays($checkIn) * 1.1, 2) }}</span>
                            </div>
                        </div>

                        <!-- Amenities -->
                        @if($roomType->amenities && count($roomType->amenities) > 0)
                            <div>
                                <p class="text-gray-600 text-sm mb-3">Amenities</p>
                                <div class="space-y-2">
                                    @foreach($roomType->amenities as $amenity)
                                        <div class="flex items-center text-sm">
                                            <span class="text-green-600 mr-2">✓</span>
                                            <span>{{ $amenity }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectRoom(roomId, price) {
            document.getElementById('room_rate').value = price;
        }
    </script>
</body>
</html>

