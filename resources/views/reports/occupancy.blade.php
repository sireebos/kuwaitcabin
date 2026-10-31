<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📊 Occupancy Report
            </h2>
            <a href="{{ route('dashboard', $property) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 text-sm font-medium">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Form -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" name="start_date" value="{{ $startDate->toDateString() }}" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" name="end_date" value="{{ $endDate->toDateString() }}" class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium">
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Rooms</h3>
                    <p class="text-4xl font-bold text-blue-600">{{ $totalRooms }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-600 text-sm font-semibold mb-2">Average Occupancy</h3>
                    <p class="text-4xl font-bold text-green-600">{{ number_format($avgOccupancy, 1) }}%</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-600 text-sm font-semibold mb-2">Period</h3>
                    <p class="text-lg font-semibold text-gray-700">{{ $startDate->format('M d') }} - {{ $endDate->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Occupancy by Date Table -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Daily Occupancy</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Date</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Occupied</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Available</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Occupancy Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($occupancyByDate as $date => $data)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold">{{ \Carbon\Carbon::parse($date)->format('M d, Y (D)') }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $data['occupied'] }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $data['available'] }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 bg-gray-200 rounded-full h-2">
                                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $data['rate'] }}%"></div>
                                            </div>
                                            <span class="font-semibold text-green-600">{{ $data['rate'] }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Occupancy by Room Type -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Occupancy by Room Type</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Room Type</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Total Rooms</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Bookings</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($occupancyByRoomType as $roomType)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold">{{ $roomType->name }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $roomType->rooms->count() }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $roomType->rooms->sum('bookings_count') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">No room types found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

