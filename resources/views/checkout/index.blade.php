<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Guest Check-out') }}
        </h2>
    </x-slot>
<div class="mb-6">
    <h1 class="text-2xl font-bold">Guest Check-out Management</h1>
    <p class="text-gray-600 mt-2">Manage guest check-outs and view checked-out guests</p>
</div>

<!-- Filter Tabs -->
<div class="mb-6 flex gap-4">
    <a href="{{ route('checkout.index', $property) }}" class="px-4 py-2 rounded-lg font-semibold @if(request('status') !== 'checked_out') bg-blue-600 text-white @else bg-gray-200 text-gray-700 @endif">
        Checked-in Guests
    </a>
    <a href="{{ route('checkout.index', ['property' => $property, 'status' => 'checked_out']) }}" class="px-4 py-2 rounded-lg font-semibold @if(request('status') === 'checked_out') bg-blue-600 text-white @else bg-gray-200 text-gray-700 @endif">
        Checked-out Guests
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold">Guest</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Room</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Check-in</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Check-out</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Balance</th>
                <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($occupiedRooms as $booking)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm">{{ $booking->guest->full_name }}</td>
                    <td class="px-6 py-4 text-sm">{{ $booking->room->room_number }}</td>
                    <td class="px-6 py-4 text-sm">{{ $booking->check_in_date }}</td>
                    <td class="px-6 py-4 text-sm">{{ $booking->check_out_date }}</td>
                    <td class="px-6 py-4 text-sm">
                        @if($booking->status === 'checked_in')
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">✓ Checked-in</span>
                        @elseif($booking->status === 'checked_out')
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">✓ Checked-out</span>
                        @else
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">{{ ucfirst($booking->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="@if(($booking->folio->balance ?? 0) > 0) text-red-600 @else text-green-600 @endif font-semibold">
                            ₦{{ number_format($booking->folio->balance ?? 0, 2) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('checkout.show', [$property, $booking]) }}" class="text-blue-600 hover:text-blue-800">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        @if(request('status') === 'checked_out')
                            No checked-out guests
                        @else
                            No occupied rooms
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $occupiedRooms->links() }}
</div>
</x-app-layout>

