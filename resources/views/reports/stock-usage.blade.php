<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📦 Stock Usage Report
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

            <!-- Summary Card -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Stock Usage Cost</h3>
                <p class="text-4xl font-bold text-purple-600">₦{{ number_format($totalUsage, 2) }}</p>
            </div>

            <!-- Stock Usage by Item -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Stock Usage by Item</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Item Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Quantity Used</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Unit</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Usage Cost</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Current Stock</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usageByItem as $itemName => $data)
                                @php
                                    $isLowStock = $data['current_stock'] <= $data['reorder_level'];
                                @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold">{{ $itemName }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $data['quantity'] }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $data['unit'] }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-orange-600">₦{{ number_format($data['cost'], 2) }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $data['current_stock'] }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($isLowStock)
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">⚠️ Low Stock</span>
                                        @else
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">✓ OK</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No stock usage found for this period</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- All Inventory Items -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">All Inventory Items</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Item Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Current Stock</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Reorder Level</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Unit</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventoryItems as $item)
                                @php
                                    $isLowStock = $item->quantity <= $item->reorder_level;
                                @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold">{{ $item->name }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $item->reorder_level }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $item->unit }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($isLowStock)
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">⚠️ Low Stock</span>
                                        @else
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">✓ OK</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No inventory items found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

