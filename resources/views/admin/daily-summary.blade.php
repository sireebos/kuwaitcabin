<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Daily Summary - {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
            </h2>
            <div class="flex gap-2">
                <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium print:hidden">
                    🖨️ Print
                </button>
                <a href="{{ route('dashboard', $property) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 text-sm font-medium print:hidden">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <style media="print">
        @page {
            size: A4;
            margin: 0.5cm;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .print\:hidden {
            display: none !important;
        }

        .bg-white {
            background-color: white !important;
            border: 1px solid #e5e7eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f3f4f6 !important;
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Print Header -->
            <div class="hidden print:block mb-8 text-center border-b-2 pb-4">
                <h1 class="text-2xl font-bold">{{ $property->display_name ?? $property->name }}</h1>
                <p class="text-gray-600">Daily Summary Report</p>
                <p class="text-gray-600">{{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}</p>
                <p class="text-sm text-gray-500 mt-2">Printed on: {{ now()->format('M d, Y H:i') }}</p>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8 print:hidden">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter Daily Summary</h3>
                <form method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                            <input type="date" name="date" value="{{ $date }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by User</label>
                            @if(auth()->user()->isAdmin())
                            <select name="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $filterUser == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @else
                            <select name="user_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" disabled>
                                <option value="{{ auth()->user()->id }}" selected>
                                    {{ auth()->user()->name }}
                                </option>
                            </select>
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="" {{ !$filterStatus ? 'selected' : '' }}>All Status</option>
                                <option value="pending" {{ $filterStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $filterStatus === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="failed" {{ $filterStatus === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-600 text-white px-8 py-2 rounded-lg hover:bg-blue-700 font-medium transition">
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.daily-summary', $property) }}" class="bg-gray-400 text-white px-8 py-2 rounded-lg hover:bg-gray-500 font-medium transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Bookings</h3>
                    <p class="text-4xl font-bold text-blue-600">₦{{ number_format($totalPayments, 2) }}</p>
                    <p class="text-gray-500 text-xs mt-2">{{ $payments->count() }} transactions</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-600 text-sm font-semibold mb-2">Total Restaurant Sales</h3>
                    <p class="text-4xl font-bold text-green-600">₦{{ number_format($totalOrders, 2) }}</p>
                    <p class="text-gray-500 text-xs mt-2">{{ $orders->count() }} orders</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-600 text-sm font-semibold mb-2">Grand Total</h3>
                    <p class="text-4xl font-bold text-purple-600">₦{{ number_format($grandTotal, 2) }}</p>
                    <p class="text-gray-500 text-xs mt-2">All transactions</p>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Booking Payments ({{ $payments->count() }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Booking Ref</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Guest</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Amount</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Method</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold">{{ $payment->booking->booking_reference ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $payment->booking->guest->full_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-green-600">₦{{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-6 py-4 text-sm">{{ ucfirst($payment->payment_method) }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $payment->created_at->format('H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No booking payments today</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold">Restaurant Orders ({{ $orders->count() }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Order #</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">User</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Amount</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Method</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold">{{ $order->order_number }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $order->user?->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-green-600">₦{{ number_format($order->total, 2) }}</td>
                                    <td class="px-6 py-4 text-sm">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $order->paid_at->format('H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No restaurant orders today</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Low Stock Items -->
            @if($lowStockItems->count() > 0)
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-bold text-red-600">⚠️ Low Stock Items ({{ $lowStockItems->count() }})</h3>
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
                            @foreach($lowStockItems as $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-semibold">{{ $item->name }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $item->reorder_level }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $item->unit }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-semibold">Low Stock</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

