<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reopen Bar Order') }}
        </h2>
    </x-slot>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
            <h3 class="text-lg font-bold text-blue-900">Reopening Order for: {{ $order->customer_name }}</h3>
            <p class="text-sm text-blue-700">Order #{{ $order->order_number }} - Created at {{ $order->created_at->format('M d, Y H:i') }}</p>
        </div>

        <h3 class="text-lg font-bold mb-4">Current Items in Order</h3>
        <div class="overflow-x-auto mb-6">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Item</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Qty</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Price</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr class="border-b">
                            <td class="px-4 py-2 text-sm">{{ $item->inventoryItem?->name ?? $item->menuItem?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-sm">{{ $item->quantity }}</td>
                            <td class="px-4 py-2 text-sm">₦{{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-4 py-2 text-sm font-semibold">₦{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end mb-6">
            <div class="w-64">
                <div class="flex justify-between mb-2">
                    <span>Subtotal:</span>
                    <span>₦{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if ($order->discount > 0)
                    <div class="flex justify-between mb-2 text-red-600">
                        <span>Discount:</span>
                        <span>-₦{{ number_format($order->discount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between mb-2">
                    <span>Tax (10%):</span>
                    <span>₦{{ number_format($order->tax, 2) }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold border-t pt-2">
                    <span>Total:</span>
                    <span>₦{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('properties.bar-orders.continue', [$property, $order]) }}" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center font-semibold">
                Continue Adding Items
            </a>
            <a href="{{ route('properties.bar-orders.show', [$property, $order]) }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 text-center font-semibold">
                View Full Order
            </a>
            <a href="{{ route('properties.bar-orders.index', $property) }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 text-center font-semibold">
                Back to Orders
            </a>
        </div>
    </div>
</div>
</x-app-layout>

