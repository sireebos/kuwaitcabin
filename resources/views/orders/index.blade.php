<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Orders</h1>
    <a href="{{ route('orders.pos', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        New Order (POS)
    </a>
</div>

<!-- Date Filter -->
<div class="mb-6 bg-white rounded-lg shadow p-4">
    <form method="GET" action="{{ route('properties.orders.index', $property) }}" class="space-y-4">
        <div class="flex items-center gap-4 flex-wrap">
            <div class="flex items-center gap-2">
                <label for="date" class="text-sm font-medium text-gray-700">Single Date:</label>
                <input type="date" id="date" name="date" value="{{ request('date', $date) }}" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="text-gray-500">OR</div>

            <div class="flex items-center gap-2">
                <label for="start_date" class="text-sm font-medium text-gray-700">Date Range:</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="From">
                <span class="text-gray-500">to</span>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="To">
            </div>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-medium">
                Filter
            </button>
            <a href="{{ route('properties.orders.index', $property) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 text-sm font-medium">
                Reset
            </a>
        </div>
    </form>
</div>

@if(auth()->user()->isAdmin())
    <div id="bulkDeleteSection" class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 hidden">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm font-semibold text-red-800">
                    <span id="selectedCount">0</span> order(s) selected
                </p>
            </div>
            <button type="button" onclick="bulkDeleteOrders()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 font-semibold">
                Delete Selected
            </button>
        </div>
    </div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                @if(auth()->user()->isAdmin())
                    <th class="px-6 py-3 text-left text-sm font-semibold">
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="rounded">
                    </th>
                @endif
                <th class="px-6 py-3 text-left text-sm font-semibold">Order ID</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Type</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Table</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Items</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Total</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Date</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr class="border-b hover:bg-gray-50">
                    @if(auth()->user()->isAdmin())
                        <td class="px-6 py-4 text-sm">
                            <input type="checkbox" class="order-checkbox rounded" value="{{ $order->id }}" onchange="updateBulkDeleteUI()">
                        </td>
                    @endif
                    <td class="px-6 py-4 text-sm font-semibold">#{{ $order->id }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($order->type === 'dine_in') bg-blue-100 text-blue-800
                            @elseif($order->type === 'takeaway') bg-green-100 text-green-800
                            @else bg-purple-100 text-purple-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->type)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $order->table?->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $order->items->count() }}</td>
                    <td class="px-6 py-4 text-sm font-semibold">₦{{ number_format($order->total, 2) }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'preparing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'ready') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $order->created_at->format('M d, Y H:i') }}</td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('properties.orders.show', [$property, $order]) }}" class="text-blue-600 hover:text-blue-800">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No orders found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(auth()->user()->isAdmin())
<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.order-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    updateBulkDeleteUI();
}

function updateBulkDeleteUI() {
    const checkboxes = document.querySelectorAll('.order-checkbox:checked');
    const bulkDeleteSection = document.getElementById('bulkDeleteSection');
    const selectedCount = document.getElementById('selectedCount');

    selectedCount.textContent = checkboxes.length;

    if (checkboxes.length > 0) {
        bulkDeleteSection.classList.remove('hidden');
    } else {
        bulkDeleteSection.classList.add('hidden');
    }
}

function bulkDeleteOrders() {
    const checkboxes = document.querySelectorAll('.order-checkbox:checked');

    if (checkboxes.length === 0) {
        alert('Please select at least one order');
        return;
    }

    if (!confirm(`Are you sure you want to delete ${checkboxes.length} order(s)? This action cannot be undone.`)) {
        return;
    }

    const orderIds = Array.from(checkboxes).map(cb => cb.value);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/properties/{{ $property->id }}/orders/bulk-delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ order_ids: orderIds })
    })
    .then(response => {
        if (response.ok) {
            window.location.reload();
        } else {
            return response.json().then(data => {
                alert('Error: ' + (data.message || 'Failed to delete orders'));
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error deleting orders: ' + error.message);
    });
}
</script>
@endif
</x-app-layout>

