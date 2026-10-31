<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Order') }}
        </h2>
    </x-slot>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Edit Order #{{ $order->id }}</h2>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Order Type</label>
                <p class="text-gray-600">{{ ucfirst(str_replace('_', ' ', $order->type)) }}</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <p class="text-gray-600">{{ ucfirst($order->status) }}</p>
            </div>
        </div>

        <hr class="my-6">

        <h3 class="text-lg font-bold mb-4">Order Items</h3>
        <div class="overflow-x-auto mb-6">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Item</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Qty</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Price</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Modifiers</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Notes</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Subtotal</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr class="border-b">
                            <td class="px-4 py-2 text-sm">{{ $item->menuItem->name }}</td>
                            <td class="px-4 py-2 text-sm">{{ $item->quantity }}</td>
                            <td class="px-4 py-2 text-sm">₦{{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-4 py-2 text-sm">
                                @if ($item->modifiers)
                                    <div class="text-xs">
                                        @foreach ($item->modifiers as $modifier)
                                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded mb-1">{{ $modifier }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm">
                                @if ($item->special_instructions)
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">{{ Str::limit($item->special_instructions, 30) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm font-semibold">₦{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                            <td class="px-4 py-2 text-sm">
                                <form method="POST" action="{{ route('orders.removeItem', [$property, $order, $item]) }}" class="inline" onsubmit="return confirm('Remove this item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <hr class="my-6">

        <!-- Add Item Form -->
        <div class="bg-blue-50 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-bold mb-4">Add Item to Order</h3>
            <form method="POST" action="{{ route('orders.addItem', [$property, $order]) }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Menu Item *</label>
                        <select name="menu_item_id" required class="w-full px-3 py-2 border border-gray-300 rounded">
                            <option value="">-- Select Item --</option>
                            @foreach ($menus as $menu)
                                @foreach ($menu->categories as $category)
                                    @foreach ($category->items as $item)
                                        @if ($item->is_available)
                                            <option value="{{ $item->id }}">{{ $item->name }} (₦{{ number_format($item->price, 2) }})</option>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity *</label>
                        <input type="number" name="quantity" value="1" min="1" required class="w-full px-3 py-2 border border-gray-300 rounded">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Modifiers (comma-separated)</label>
                    <input type="text" name="modifiers" placeholder="e.g., Extra cheese, No onions, Spicy" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Special Instructions</label>
                    <textarea name="special_instructions" rows="2" placeholder="e.g., Cook well done, No salt" class="w-full px-3 py-2 border border-gray-300 rounded"></textarea>
                </div>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-semibold">
                    Add Item
                </button>
            </form>
        </div>

        <hr class="my-6">

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

        <div class="flex gap-2">
            <a href="{{ route('properties.orders.index', $property) }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 text-center font-semibold">
                Back to Orders
            </a>
            <a href="{{ route('properties.orders.show', [$property, $order]) }}" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center font-semibold">
                View Order
            </a>
        </div>
    </div>
</div>
</x-app-layout>

