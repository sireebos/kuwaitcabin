<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking Details') }}
        </h2>
    </x-slot>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Booking Details -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">Booking Information</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 text-sm">Booking Reference</p>
                    <p class="font-semibold">{{ $booking->booking_reference }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Status</p>
                    <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Guest</p>
                    <p class="font-semibold">{{ $booking->guest->full_name }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Room</p>
                    <p class="font-semibold">{{ $booking->room->room_number ?? 'Not assigned' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Check-in</p>
                    <p class="font-semibold">{{ $booking->check_in_date }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Check-out</p>
                    <p class="font-semibold">{{ $booking->check_out_date }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Number of Nights</p>
                    <p class="font-semibold">{{ $booking->number_of_nights }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Number of Guests</p>
                    <p class="font-semibold">{{ $booking->number_of_guests }}</p>
                </div>
            </div>
        </div>

        <!-- Folio -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Folio</h2>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Room Charges:</span>
                    <span>₦{{ number_format($booking->folio->room_charges ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Restaurant Charges:</span>
                    <span>₦{{ number_format($booking->folio->restaurant_charges ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Other Charges:</span>
                    <span>₦{{ number_format($booking->folio->other_charges ?? 0, 2) }}</span>
                </div>
                <div class="border-t pt-2 flex justify-between font-bold">
                    <span>Total Charges:</span>
                    <span>₦{{ number_format($booking->folio->total_charges ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Payments Received:</span>
                    <span>₦{{ number_format($booking->folio->payments_received ?? 0, 2) }}</span>
                </div>
                <div class="border-t pt-2 flex justify-between font-bold text-lg">
                    <span>Balance:</span>
                    @php
                        $balance = ($booking->folio->total_charges ?? 0) - ($booking->folio->payments_received ?? 0);
                    @endphp
                    <span class="@if($balance > 0) text-red-600 @elseif($balance < 0) text-blue-600 @else text-green-600 @endif">
                        ₦{{ number_format($balance, 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Actions</h2>
        <div class="space-y-2">
            @if($booking->status === 'pending')
                <form method="POST" action="{{ route('bookings.confirm', [$property, $booking]) }}">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Confirm Booking
                    </button>
                </form>
            @endif

            @if($booking->status === 'confirmed')
                <a href="{{ route('checkin.create', [$property, $booking]) }}" class="block w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center">
                    Check-in
                </a>
            @endif

            @if($booking->status === 'checked_in')
                <a href="{{ route('checkout.show', [$property, $booking]) }}" class="block w-full bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-700 text-center">
                    Check-out
                </a>
            @endif

            <button type="button" onclick="openPaymentModal()" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 font-semibold">
                Record Payment
            </button>

            <a href="{{ route('folio.show', [$property, $booking]) }}" class="block w-full bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 text-center">
                View Folio
            </a>

            <!-- Notification Actions -->
            <div class="border-t pt-3 mt-3">
                <p class="text-sm font-semibold text-gray-600 mb-2">📢 Send Notification</p>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('bookings.notify-email', [$property, $booking]) }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-1 bg-blue-100 text-blue-700 px-3 py-2 rounded hover:bg-blue-200 text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Email
                        </button>
                    </form>
                    @if($property->phone)
                        <a href="{{ route('bookings.notify-whatsapp', [$property, $booking]) }}" target="_blank" class="flex-1 flex items-center justify-center gap-1 bg-emerald-100 text-emerald-700 px-3 py-2 rounded hover:bg-emerald-200 text-sm font-medium">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp
                        </a>
                    @endif
                </div>
            </div>

            <a href="{{ route('properties.bookings.index', $property) }}" class="block w-full bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 text-center">
                Back to Bookings
            </a>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Record Payment</h2>

        <form method="POST" action="{{ route('folio.recordPayment', [$property, $booking]) }}">
            @csrf

            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount (₦)</label>
                <input type="number" id="amount" name="amount" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold text-lg" placeholder="0.00">
                <p class="text-xs text-gray-500 mt-1">Balance due will be automatically filled</p>
            </div>

            <div class="mb-4">
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <select id="payment_method" name="payment_method" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Payment Method</option>
                    <option value="cash">Cash</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="debit_card">Debit Card</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="check">Check</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-medium">
                    Record Payment
                </button>
                <button type="button" onclick="closePaymentModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 font-medium">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openPaymentModal() {
    // Calculate balance due from folio
    const totalCharges = {{ ($booking->folio->total_charges ?? 0) }};
    const paymentsReceived = {{ ($booking->folio->payments_received ?? 0) }};
    const balanceDue = Math.max(0, totalCharges - paymentsReceived);

    // Populate the amount field with balance due
    document.getElementById('amount').value = balanceDue.toFixed(2);

    // Open modal
    document.getElementById('paymentModal').classList.remove('hidden');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('paymentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePaymentModal();
    }
});
</script>
</x-app-layout>

