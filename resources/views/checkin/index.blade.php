<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Guest Check-in') }}
        </h2>
    </x-slot>
<div class="mb-6">
    <h1 class="text-2xl font-bold">Upcoming Check-ins</h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($upcomingCheckIns as $booking)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-4">
                <h3 class="text-lg font-bold">{{ $booking->guest->full_name }}</h3>
                <p class="text-gray-600 text-sm">{{ $booking->guest->email }}</p>
            </div>

            <div class="space-y-2 mb-4 text-sm">
                <div>
                    <p class="text-gray-600">Booking Reference</p>
                    <p class="font-semibold">{{ $booking->booking_reference }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Check-in Date</p>
                    <p class="font-semibold">{{ $booking->check_in_date }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Number of Guests</p>
                    <p class="font-semibold">{{ $booking->number_of_guests }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Number of Nights</p>
                    <p class="font-semibold">{{ $booking->number_of_nights }}</p>
                </div>
            </div>

            <a href="{{ route('checkin.create', [$property, $booking]) }}" class="block w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-center">
                Check-in Guest
            </a>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-500 mb-4">No upcoming check-ins</p>
            <p class="text-gray-400 text-sm mb-4">Bookings ready for check-in will appear here when:</p>
            <ul class="text-gray-400 text-sm space-y-1">
                <li>✓ Check-in date is today or earlier</li>
                <li>✓ Check-out date is today or later</li>
                <li>✓ Booking status is pending, confirmed, or checked_in</li>
            </ul>
            <a href="{{ route('properties.bookings.index', $property) }}" class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                View All Bookings
            </a>
        </div>
    @endforelse
</div>
</x-app-layout>

