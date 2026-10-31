<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bookings') }}
        </h2>
    </x-slot>
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Bookings</h1>
    <div class="flex gap-2">
        @if(auth()->user()->isAdmin())
            <button id="deleteSelectedBtn" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 hidden" onclick="deleteSelectedBookings()">
                Delete Selected
            </button>
        @endif
        <a href="{{ route('properties.bookings.create', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            New Booking
        </a>
    </div>
</div>

<!-- Date Filter -->
<div class="mb-6 bg-white rounded-lg shadow p-4">
    <form method="GET" action="{{ route('properties.bookings.index', $property) }}" class="space-y-4">
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
            <a href="{{ route('properties.bookings.index', $property) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 text-sm font-medium">
                Reset
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold">
                    <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" class="w-4 h-4">
                </th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Reference</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Guest</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Room</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Check-in</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Check-out</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Date Created</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Total</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $booking)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm">
                        <input type="checkbox" class="booking-checkbox w-4 h-4" value="{{ $booking->id }}" onchange="updateDeleteButton()">
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $booking->booking_reference }}</td>
                    <td class="px-6 py-4 text-sm">{{ $booking->guest->full_name }}</td>
                    <td class="px-6 py-4 text-sm">{{ $booking->room->room_number ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $booking->check_in_date }}</td>
                    <td class="px-6 py-4 text-sm">{{ $booking->check_out_date }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->status === 'confirmed') bg-blue-100 text-blue-800
                            @elseif($booking->status === 'checked_in') bg-green-100 text-green-800
                            @elseif($booking->status === 'checked_out') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $booking->created_at->format('M d, Y H:i') }}</td>
                    <td class="px-6 py-4 text-sm">₦{{ number_format($booking->total_amount, 2) }}</td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('properties.bookings.show', [$property, $booking]) }}" class="text-blue-600 hover:text-blue-800">View</a>
                        @if($booking->status === 'pending')
                            <form method="POST" action="{{ route('bookings.confirm', [$property, $booking]) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-800 ml-2">Confirm</button>
                            </form>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('properties.bookings.destroy', [$property, $booking]) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this booking? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 ml-2">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">No bookings found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function toggleSelectAll(checkbox) {
    const bookingCheckboxes = document.querySelectorAll('.booking-checkbox');
    bookingCheckboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
    updateDeleteButton();
}

function updateDeleteButton() {
    const bookingCheckboxes = document.querySelectorAll('.booking-checkbox');
    const selectedCount = Array.from(bookingCheckboxes).filter(cb => cb.checked).length;
    const deleteBtn = document.getElementById('deleteSelectedBtn');

    if (selectedCount > 0) {
        deleteBtn.classList.remove('hidden');
        deleteBtn.textContent = `Delete Selected (${selectedCount})`;
    } else {
        deleteBtn.classList.add('hidden');
    }
}

function deleteSelectedBookings() {
    const bookingCheckboxes = document.querySelectorAll('.booking-checkbox:checked');
    const selectedIds = Array.from(bookingCheckboxes).map(cb => cb.value);

    if (selectedIds.length === 0) {
        alert('Please select at least one booking to delete');
        return;
    }

    if (!confirm(`Are you sure you want to delete ${selectedIds.length} booking(s)? This action cannot be undone.`)) {
        return;
    }

    // Create a form and submit it
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("bookings.deleteMultiple", $property) }}';

    // Add CSRF token
    const csrfToken = document.querySelector('input[name="_token"]');
    if (csrfToken) {
        form.appendChild(csrfToken.cloneNode(true));
    }

    // Add booking IDs
    selectedIds.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'booking_ids[]';
        input.value = id;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
}
</script>
</x-app-layout>

