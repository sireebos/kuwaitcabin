<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🍽️ Food Cost Report
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
                    <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Food Cost</h3>
                    <p class="text-4xl font-bold text-red-600">₦{{ number_format($totalFoodCost, 2) }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Revenue</h3>
                    <p class="text-4xl font-bold text-green-600">₦{{ number_format($totalRevenue, 2) }}</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-600 text-sm font-semibold mb-2">Food Cost %</h3>
                    <p class="text-4xl font-bold text-orange-600">{{ $foodCostPercentage }}%</p>
                    <p class="text-gray-500 text-xs mt-2">Ideal: 28-35%</p>
                </div>
            </div>

            <!-- Food Cost by Item -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Food Cost by Item</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Item Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Quantity Sold</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Food Cost</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Revenue</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Margin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($foodCostByItem as $itemName => $data)
                                @php
                                    $margin = $data['revenue'] - $data['cost'];
                                    $marginPercent = $data['revenue'] > 0 ? round(($margin / $data['revenue']) * 100, 2) : 0;
                                @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold">{{ $itemName }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $data['quantity'] }}</td>
                                    <td class="px-6 py-4 text-sm text-red-600 font-semibold">₦{{ number_format($data['cost'], 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-green-600 font-semibold">₦{{ number_format($data['revenue'], 2) }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">{{ $marginPercent }}%</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No orders found for this period</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

