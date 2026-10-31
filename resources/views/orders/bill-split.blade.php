<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bill Split - Order #') }}{{ $order->order_number }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <!-- Order Summary -->
            <div class="mb-6 pb-6 border-b">
                <h3 class="text-lg font-bold mb-4">Order Summary</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">Order Total:</p>
                        <p class="text-2xl font-bold">₦{{ number_format($order->total, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Number of Guests:</p>
                        <p class="text-2xl font-bold" id="guestCount">0</p>
                    </div>
                </div>
            </div>

            <!-- Bill Splits Table -->
            <div class="mb-6">
                <h3 class="text-lg font-bold mb-4">Bill Splits</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Guest Name</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Amount</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Payment Method</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Status</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody id="billSplitsTable">
                            @forelse ($order->billSplits as $split)
                                <tr class="border-b">
                                    <td class="px-4 py-2 text-sm">{{ $split->guest_name }}</td>
                                    <td class="px-4 py-2 text-sm font-semibold">₦{{ number_format($split->amount, 2) }}</td>
                                    <td class="px-4 py-2 text-sm">{{ $split->payment_method ?? '-' }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        <span class="px-2 py-1 rounded text-xs font-semibold
                                            @if($split->status === 'paid') bg-green-100 text-green-800
                                            @elseif($split->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($split->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-sm">
                                        <button onclick="editSplit({{ $split->id }})" class="text-blue-600 hover:text-blue-800">Edit</button>
                                        <button onclick="deleteSplit({{ $split->id }})" class="text-red-600 hover:text-red-800 ml-2">Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">No bill splits yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Bill Split Form -->
            <div class="bg-blue-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-bold mb-4">Add Bill Split</h3>
                <form method="POST" action="{{ route('bill-splits.store', [$property, $order]) }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Guest Name *</label>
                            <input type="text" name="guest_name" required class="w-full px-3 py-2 border border-gray-300 rounded">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Amount *</label>
                            <input type="number" name="amount" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Payment Method</label>
                        <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded">
                            <option value="">-- Select Method --</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="transfer">Bank Transfer</option>
                            <option value="mobile_money">Mobile Money</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-semibold">
                        Add Split
                    </button>
                </form>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <a href="{{ route('properties.orders.show', [$property, $order]) }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 text-center font-semibold">
                    Back to Order
                </a>
            </div>
        </div>
    </div>

    <script>
        function updateGuestCount() {
            const rows = document.querySelectorAll('#billSplitsTable tr:not(:has(td:nth-child(1):contains("No bill")))');
            document.getElementById('guestCount').textContent = rows.length;
        }

        function editSplit(id) {
            alert('Edit functionality coming soon');
        }

        function deleteSplit(id) {
            if (confirm('Are you sure you want to delete this split?')) {
                // Delete via AJAX
                alert('Delete functionality coming soon');
            }
        }

        updateGuestCount();
    </script>
</x-app-layout>

