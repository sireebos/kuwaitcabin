<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daily Bookings Report') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" action="{{ route('reports.daily-bookings', $property) }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sort By</label>
                    <select name="sort_by" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="date" @selected($sortBy === 'date')>Date</option>
                        <option value="amount" @selected($sortBy === 'amount')>Amount</option>
                        <option value="user" @selected($sortBy === 'user')>User</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Order</label>
                    <select name="sort_order" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="desc" @selected($sortOrder === 'desc')>Descending</option>
                        <option value="asc" @selected($sortOrder === 'asc')>Ascending</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Filter
                    </button>
                    <a href="{{ route('reports.daily-bookings-pdf', $property) }}?{{ request()->getQueryString() }}" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-center">
                        📄 PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Bookings</p>
                <p class="text-3xl font-bold text-blue-600">{{ $totalBookings }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Revenue</p>
                <p class="text-3xl font-bold text-green-600">₦{{ number_format(abs($totalRevenue), 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Average Booking</p>
                <p class="text-3xl font-bold text-purple-600">₦{{ number_format(abs($totalBookings > 0 ? $totalRevenue / $totalBookings : 0), 2) }}</p>
            </div>
        </div>

        <!-- Bookings by User -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold mb-4">Bookings by User</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Bookings</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Total Revenue</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Average</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookingsByUser as $booking)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm">{{ $booking->name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $booking->booking_count }}</td>
                                <td class="px-6 py-4 text-sm font-semibold">₦{{ number_format(abs($booking->total_revenue), 2) }}</td>
                                <td class="px-6 py-4 text-sm">₦{{ number_format(abs($booking->total_revenue / $booking->booking_count), 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No booking data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detailed Bookings -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Detailed Bookings</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Guest</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Room</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Check-in</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Check-out</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Amount</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm">{{ $booking->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm">{{ $booking->user?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm">{{ $booking->guest?->full_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm">{{ $booking->room?->room_number ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm">{{ $booking->check_in_date }}</td>
                                <td class="px-6 py-4 text-sm">{{ $booking->check_out_date }}</td>
                                <td class="px-6 py-4 text-sm font-semibold">₦{{ number_format(abs($booking->total_amount), 2) }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($booking->status === 'checked_in') bg-blue-100 text-blue-800
                                        @elseif($booking->status === 'checked_out') bg-gray-100 text-gray-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">No bookings found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

