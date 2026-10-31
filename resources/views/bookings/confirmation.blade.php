<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Success Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-800 text-white py-12">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <div class="text-6xl mb-4">✓</div>
                <h1 class="text-4xl font-bold">Booking Confirmed!</h1>
                <p class="text-green-100 mt-2">Thank you for your reservation</p>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 py-12">
            <!-- Confirmation Message -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                <p class="text-green-800">
                    A confirmation email has been sent to <strong>{{ $booking->guest->email }}</strong>. 
                    Please check your email for booking details and important information.
                </p>
            </div>

            <!-- Booking Details Card -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div>
                        <h2 class="text-2xl font-bold mb-6">Booking Details</h2>

                        <!-- Booking Number -->
                        <div class="mb-6 pb-6 border-b">
                            <p class="text-gray-600 text-sm">Booking Number</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $booking->booking_reference }}</p>
                        </div>

                        <!-- Guest Information -->
                        <div class="mb-6 pb-6 border-b">
                            <p class="text-gray-600 text-sm mb-2">Guest Name</p>
                            <p class="font-semibold">{{ $booking->guest->first_name }} {{ $booking->guest->last_name }}</p>
                            <p class="text-gray-600 text-sm mt-3">Email</p>
                            <p class="font-semibold">{{ $booking->guest->email }}</p>
                            <p class="text-gray-600 text-sm mt-3">Phone</p>
                            <p class="font-semibold">{{ $booking->guest->phone }}</p>
                        </div>

                        <!-- Room Information -->
                        <div class="mb-6 pb-6 border-b">
                            <p class="text-gray-600 text-sm">Room</p>
                            <p class="font-semibold">{{ $booking->room->room_number }} ({{ $booking->room->roomType->name }})</p>
                            <p class="text-gray-600 text-sm mt-3">Property</p>
                            <p class="font-semibold">{{ $booking->property->name }}</p>
                        </div>

                        <!-- Special Requests -->
                        @if($booking->special_requests)
                            <div>
                                <p class="text-gray-600 text-sm">Special Requests</p>
                                <p class="font-semibold">{{ $booking->special_requests }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column -->
                    <div>
                        <h2 class="text-2xl font-bold mb-6">Stay Details</h2>

                        <!-- Check-in -->
                        <div class="mb-6 pb-6 border-b">
                            <p class="text-gray-600 text-sm">Check-in</p>
                            <p class="text-2xl font-bold">{{ $booking->check_in_date->format('M d, Y') }}</p>
                            <p class="text-gray-600 text-sm mt-2">Time: {{ $booking->property->check_in_time }}:00</p>
                        </div>

                        <!-- Check-out -->
                        <div class="mb-6 pb-6 border-b">
                            <p class="text-gray-600 text-sm">Check-out</p>
                            <p class="text-2xl font-bold">{{ $booking->check_out_date->format('M d, Y') }}</p>
                            <p class="text-gray-600 text-sm mt-2">Time: {{ $booking->property->check_out_time }}:00</p>
                        </div>

                        <!-- Duration -->
                        <div class="mb-6 pb-6 border-b">
                            <p class="text-gray-600 text-sm">Duration</p>
                            <p class="font-semibold">{{ $booking->number_of_nights }} night(s)</p>
                        </div>

                        <!-- Guests -->
                        <div>
                            <p class="text-gray-600 text-sm">Number of Guests</p>
                            <p class="font-semibold">{{ $booking->number_of_guests }} guest(s)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Summary -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold mb-6">Price Summary</h2>
                <div class="space-y-4">
                    <div class="flex justify-between text-lg">
                        <span class="text-gray-600">Room Rate</span>
                        <span class="font-semibold">₦{{ number_format($booking->room_rate, 2) }} per night</span>
                    </div>
                    <div class="flex justify-between text-lg">
                        <span class="text-gray-600">Number of Nights</span>
                        <span class="font-semibold">{{ $booking->number_of_nights }}</span>
                    </div>
                    <div class="flex justify-between text-lg border-t pt-4">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold">₦{{ number_format($booking->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg">
                        <span class="text-gray-600">Tax (10%)</span>
                        <span class="font-semibold">₦{{ number_format($booking->tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-2xl font-bold border-t pt-4 text-blue-600">
                        <span>Total Amount</span>
                        <span>₦{{ number_format($booking->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Property Information -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold mb-6">Property Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-gray-600 text-sm">Address</p>
                        <p class="font-semibold">{{ $booking->property->address }}</p>
                        <p class="font-semibold">{{ $booking->property->city }}, {{ $booking->property->state }} {{ $booking->property->postal_code }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Contact</p>
                        <p class="font-semibold">{{ $booking->property->phone }}</p>
                        <p class="font-semibold">{{ $booking->property->email }}</p>
                        @if($booking->property->website)
                            <p class="font-semibold"><a href="{{ $booking->property->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ $booking->property->website }}</a></p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Important Information -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 mb-8">
                <h2 class="text-xl font-bold text-blue-900 mb-4">Important Information</h2>
                <ul class="space-y-3 text-blue-900">
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-3">•</span>
                        <span>Check-in time is {{ $booking->property->check_in_time }}:00 and check-out time is {{ $booking->property->check_out_time }}:00</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-3">•</span>
                        <span>Please bring a valid ID at check-in</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-3">•</span>
                        <span>For any changes or cancellations, please contact the property directly</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-blue-600 mr-3">•</span>
                        <span>A confirmation email with all details has been sent to your email address</span>
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-center">
                <a href="{{ route('search.index') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                    Make Another Booking
                </a>
                <button onclick="window.print()" class="bg-gray-600 text-white px-8 py-3 rounded-lg hover:bg-gray-700 font-semibold">
                    Print Confirmation
                </button>
            </div>
        </div>
    </div>
</body>
</html>

