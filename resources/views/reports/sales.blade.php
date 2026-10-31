<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daily Sales Report') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" action="{{ route('reports.daily-sales', $property) }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
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
                    <a href="{{ route('reports.daily-sales-pdf', $property) }}?{{ request()->getQueryString() }}" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-center">
                        📄 PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Sales</p>
                <p class="text-3xl font-bold text-blue-600">₦{{ number_format(abs($totalSales), 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Transactions</p>
                <p class="text-3xl font-bold text-green-600">{{ $payments->total() + $orders->count() }}</p>
            </div>
        </div>

        <!-- Sales by User -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-bold mb-4">Sales by User</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Transactions</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Total Sales</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Average</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Combine booking payments and restaurant orders by user
                            $userSales = [];

                            // Add booking payments
                            foreach($salesByUser as $sale) {
                                if (!isset($userSales[$sale->name])) {
                                    $userSales[$sale->name] = [
                                        'transactions' => 0,
                                        'total' => 0
                                    ];
                                }
                                $userSales[$sale->name]['transactions'] += $sale->transaction_count;
                                $userSales[$sale->name]['total'] += $sale->total;
                            }

                            // Add restaurant orders
                            foreach($orders as $order) {
                                $userName = $order->user?->name ?? 'N/A';
                                if (!isset($userSales[$userName])) {
                                    $userSales[$userName] = [
                                        'transactions' => 0,
                                        'total' => 0
                                    ];
                                }
                                $userSales[$userName]['transactions'] += 1;
                                $userSales[$userName]['total'] += $order->total;
                            }

                            // Sort by total descending
                            uasort($userSales, function($a, $b) {
                                return $b['total'] <=> $a['total'];
                            });
                        @endphp

                        @forelse($userSales as $userName => $sale)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm">{{ $userName }}</td>
                                <td class="px-6 py-4 text-sm">{{ $sale['transactions'] }}</td>
                                <td class="px-6 py-4 text-sm font-semibold">₦{{ number_format(abs($sale['total']), 2) }}</td>
                                <td class="px-6 py-4 text-sm">₦{{ number_format(abs($sale['total'] / $sale['transactions']), 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No sales data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detailed Transactions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Detailed Transactions</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">User</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Guest</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Amount</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm"><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold">Booking</span></td>
                                <td class="px-6 py-4 text-sm">{{ $payment->booking?->user?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm">{{ $payment->booking?->guest?->full_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm font-semibold">₦{{ number_format(abs($payment->amount), 2) }}</td>
                                <td class="px-6 py-4 text-sm">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                            </tr>
                        @empty
                            @if($orders->isEmpty())
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No transactions found</td>
                                </tr>
                            @endif
                        @endforelse

                        @forelse($orders as $order)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm">{{ $order->paid_at->format('M d, Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">Restaurant</span></td>
                                <td class="px-6 py-4 text-sm">{{ $order->user?->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm">Order #{{ $order->order_number }}</td>
                                <td class="px-6 py-4 text-sm font-semibold">₦{{ number_format($order->total, 2) }}</td>
                                <td class="px-6 py-4 text-sm">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

