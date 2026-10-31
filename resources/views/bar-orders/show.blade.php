<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bar Order Details') }}
        </h2>
    </x-slot>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Order ID</h3>
                <p class="text-2xl font-bold">#{{ $order->id }}</p>
            </div>
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Customer/Table</h3>
                <p class="text-lg font-semibold">{{ $order->customer_name ?? 'N/A' }}</p>
            </div>
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Status</h3>
                <span class="px-3 py-1 rounded text-sm font-semibold
                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->status === 'preparing') bg-blue-100 text-blue-800
                    @elseif($order->status === 'ready') bg-green-100 text-green-800
                    @elseif($order->status === 'served') bg-purple-100 text-purple-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Type</h3>
                <p>{{ ucfirst(str_replace('_', ' ', $order->type)) }}</p>
            </div>
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Bartender</h3>
                <p>{{ $order->user?->name ?? 'N/A' }}</p>
            </div>
        </div>

        <hr class="my-6">

        <h3 class="text-lg font-bold mb-4">Order Items</h3>
        <div class="overflow-x-auto">
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
                            <td class="px-4 py-2 text-sm">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-4 py-2 text-sm font-semibold">{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <hr class="my-6">

        <div class="flex justify-end mb-6">
            <div class="w-64">
                <div class="flex justify-between mb-2">
                    <span>Subtotal:</span>
                    <span>{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if ($order->discount > 0)
                    <div class="flex justify-between mb-2 text-red-600">
                        <span>Discount:</span>
                        <span>-{{ number_format($order->discount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between mb-2">
                    <span>Tax ({{ $property->tax_rate ?? 10 }}%):</span>
                    <span>{{ number_format($order->tax, 2) }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold border-t pt-2">
                    <span>Total:</span>
                    <span>{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Method Section -->
        @if($order->status !== 'closed')
            <hr class="my-6">
            <h3 class="text-lg font-bold mb-4">Payment Method</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Cash Payment -->
                <form action="{{ route('properties.bar-orders.updateStatus', [$property, $order]) }}" method="POST" class="p-4 border-2 border-green-200 rounded-lg hover:border-green-400 transition">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="closed">
                    <input type="hidden" name="payment_method" value="cash">
                    <div class="flex items-center mb-3">
                        <input type="radio" id="cash" name="payment_method_display" value="cash" class="mr-3" checked>
                        <label for="cash" class="font-semibold text-lg cursor-pointer"> Cash Payment</label>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Total Amount: <span class="font-bold text-lg">{{ number_format($order->total, 2) }}</span></p>
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-semibold">
                        Payment Complete
                    </button>
                </form>

                <!-- Card Payment -->
                <form action="{{ route('properties.bar-orders.updateStatus', [$property, $order]) }}" method="POST" class="p-4 border-2 border-blue-200 rounded-lg hover:border-blue-400 transition">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="closed">
                    <input type="hidden" name="payment_method" value="card">
                    <div class="flex items-center mb-3">
                        <input type="radio" id="card" name="payment_method_display" value="card" class="mr-3">
                        <label for="card" class="font-semibold text-lg cursor-pointer"> Card Payment</label>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Total Amount: <span class="font-bold text-lg">{{ number_format($order->total, 2) }}</span></p>
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-semibold">
                        Payment Complete
                    </button>
                </form>

                <!-- Mobile Money Payment -->
                <form action="{{ route('properties.bar-orders.updateStatus', [$property, $order]) }}" method="POST" class="p-4 border-2 border-purple-200 rounded-lg hover:border-purple-400 transition">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="closed">
                    <input type="hidden" name="payment_method" value="mobile_money">
                    <div class="flex items-center mb-3">
                        <input type="radio" id="mobile" name="payment_method_display" value="mobile_money" class="mr-3">
                        <label for="mobile" class="font-semibold text-lg cursor-pointer"> Mobile Money</label>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Total Amount: <span class="font-bold text-lg">{{ number_format($order->total, 2) }}</span></p>
                    <button type="submit" class="w-full bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 font-semibold">
                        Payment Complete
                    </button>
                </form>
            </div>
        @elseif($order->status === 'closed')
            <!-- Payment Completed Section -->
            <hr class="my-6">
            <div class="bg-green-50 border-2 border-green-400 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <span class="text-4xl mr-3"></span>
                    <h3 class="text-2xl font-bold text-green-800">Payment Completed</h3>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Payment Method</p>
                        <p class="text-lg font-bold capitalize">
                            @if($order->payment_method === 'cash')
                                 Cash
                            @elseif($order->payment_method === 'card')
                                 Card
                            @elseif($order->payment_method === 'mobile_money')
                                 Mobile Money
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Amount Paid</p>
                        <p class="text-lg font-bold">{{ number_format($order->total, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Payment Date & Time</p>
                        <p class="text-lg font-bold">{{ $order->paid_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-semibold">Order Number</p>
                        <p class="text-lg font-bold">{{ $order->order_number }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex gap-2 flex-wrap mt-6">
            <a href="{{ route('properties.bar-orders.index', $property) }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 text-center font-semibold">
                Back to Orders
            </a>
            <a href="{{ route('properties.bar-orders.receipt', [$property, $order]) }}" target="_blank" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center font-semibold">
                🖨️ Print Receipt
            </a>
            @if(auth()->user()->isAdmin())
                <form action="{{ route('properties.bar-orders.destroy', [$property, $order]) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this order?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 font-semibold">
                        Delete Order
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
