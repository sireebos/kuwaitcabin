<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Guest Name</h3>
                <p class="text-2xl font-bold">{{ $booking->guest->full_name }}</p>
            </div>
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Room</h3>
                <p class="text-2xl font-bold">{{ $booking->room->room_number }}</p>
            </div>
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Check-in Date</h3>
                <p>{{ $booking->check_in_date }}</p>
            </div>
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Check-out Date</h3>
                <p>{{ $booking->check_out_date }}</p>
            </div>
        </div>

        <hr class="my-6">

        <h3 class="text-lg font-bold mb-4">Folio Summary</h3>
        <div class="overflow-x-auto mb-6">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Description</th>
                        <th class="px-4 py-2 text-left text-sm font-semibold">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($booking->folio->items as $item)
                        <tr class="border-b">
                            <td class="px-4 py-2 text-sm">{{ $item->description }}</td>
                            <td class="px-4 py-2 text-sm text-right">₦{{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-2 text-center text-gray-500">No charges</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <hr class="my-6">

        <!-- Payment Details -->
        <h3 class="text-lg font-bold mb-4">Payment Details</h3>
        <div class="overflow-x-auto mb-6">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-4 py-2 text-left">Payment Method</th>
                        <th class="px-4 py-2 text-left">Amount</th>
                        <th class="px-4 py-2 text-left">Currency</th>
                        <th class="px-4 py-2 text-left">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($booking->payments as $payment)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                            <td class="px-4 py-2 font-semibold">{{ number_format($payment->amount, 2) }}</td>
                            <td class="px-4 py-2">{{ $payment->currency ?? '₦' }}</td>
                            <td class="px-4 py-2">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center text-gray-500">No payments recorded</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <hr class="my-6">

        <div class="flex justify-end mb-6">
            <div class="w-64">
                <div class="flex justify-between mb-2">
                    <span>Subtotal:</span>
                    <span>{{ $property->currency ?? '₦' }}{{ number_format($booking->folio->items->sum('amount'), 2) }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>Payments:</span>
                    <span class="text-green-600">-{{ $property->currency ?? '₦' }}{{ number_format($booking->payments->sum('amount'), 2) }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold border-t pt-2">
                    <span>Balance Due:</span>
                    <span class="@if(($booking->folio->balance ?? 0) > 0) text-red-600 @else text-green-600 @endif">
                        {{ $property->currency ?? '₦' }}{{ number_format($booking->folio->balance ?? 0, 2) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            <form method="POST" action="{{ route('checkout.checkout', [$property, $booking]) }}" class="flex-1" id="checkoutForm">
                @csrf
                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors" id="checkoutBtn">
                    Complete Checkout
                </button>
            </form>
            <a href="{{ route('checkout.index', $property) }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 text-center">
                Back
            </a>
        </div>

        <script>
            document.getElementById('checkoutForm').addEventListener('submit', function(e) {
                const btn = document.getElementById('checkoutBtn');
                btn.disabled = true;
                btn.textContent = 'Processing...';
            });
        </script>
    </div>
</div>
</x-app-layout>

