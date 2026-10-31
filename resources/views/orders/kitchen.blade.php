<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kitchen & Bar Orders') }}
            </h2>
            <button onclick="location.reload()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                🔄 Refresh
            </button>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        @if ($orders->isEmpty())
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <p class="text-gray-500 text-lg">No pending orders</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($orders as $order)
                    <div class="bg-white rounded-lg shadow-lg p-4 border-l-4 
                        @if($order->status === 'pending') border-yellow-500
                        @elseif($order->status === 'preparing') border-blue-500
                        @else border-gray-500
                        @endif">
                        
                        <!-- Order Header -->
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-lg font-bold">Order #{{ $order->order_number }}</h3>
                                <p class="text-sm text-gray-600">{{ $order->created_at->format('H:i') }}</p>
                            </div>
                            <span class="px-3 py-1 rounded text-sm font-semibold
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'preparing') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <!-- Table Info -->
                        @if ($order->table)
                            <p class="text-sm font-semibold text-gray-700 mb-3">
                                🪑 Table {{ $order->table->table_number }}
                            </p>
                        @endif

                        <!-- Order Items -->
                        <div class="bg-gray-50 rounded p-3 mb-4 max-h-48 overflow-y-auto">
                            @foreach ($order->items as $item)
                                <div class="mb-3 pb-3 border-b last:border-b-0">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="font-semibold text-sm">{{ $item->quantity }}x {{ $item->menuItem->name }}</p>
                                            @if ($item->modifiers)
                                                <div class="text-xs text-gray-600 mt-1">
                                                    @foreach ($item->modifiers as $modifier)
                                                        <span class="inline-block bg-blue-100 text-blue-700 px-2 py-0.5 rounded mr-1">{{ $modifier }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if ($item->special_instructions)
                                                <p class="text-xs text-orange-600 font-semibold mt-1">📝 {{ $item->special_instructions }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Status Update Buttons -->
                        <div class="flex gap-2">
                            @if ($order->status === 'pending')
                                <form method="POST" action="{{ route('orders.updateStatus', [$property, $order]) }}" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="preparing">
                                    <button type="submit" class="w-full bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 font-semibold text-sm">
                                        Start Preparing
                                    </button>
                                </form>
                            @elseif ($order->status === 'preparing')
                                <form method="POST" action="{{ route('orders.updateStatus', [$property, $order]) }}" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="served">
                                    <button type="submit" class="w-full bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700 font-semibold text-sm">
                                        Mark Ready
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        // Auto-refresh every 10 seconds
        setInterval(function() {
            location.reload();
        }, 10000);
    </script>
</x-app-layout>

