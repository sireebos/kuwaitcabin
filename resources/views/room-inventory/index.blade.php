<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Room Inventory Management') }}
        </h2>
    </x-slot>

<div class="mb-6">
    <h1 class="text-2xl font-bold mb-4">Room Inventory - {{ $property->name }}</h1>
    
    <!-- Date Filter -->
    <form method="GET" action="{{ route('properties.room-inventory.index', $property) }}" class="flex gap-4 mb-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-lg">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
            <a href="{{ route('properties.room-inventory.create', $property) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">+ Bulk Update</a>
        </div>
    </form>
</div>

<!-- Calendar View -->
<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-700 sticky left-0 bg-gray-100">Room</th>
                @foreach($dates as $date)
                    <th class="px-2 py-3 text-center font-semibold text-gray-700 whitespace-nowrap">
                        <div>{{ $date->format('M d') }}</div>
                        <div class="text-xs text-gray-500">{{ $date->format('D') }}</div>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rooms as $room)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900 sticky left-0 bg-white">
                        <div>{{ $room->room_number }}</div>
                        <div class="text-xs text-gray-500">{{ $room->roomType->name }}</div>
                    </td>
                    @foreach($dates as $date)
                        @php
                            $inventory = $room->inventory->firstWhere('date', $date->toDateString());
                            $status = $inventory?->status ?? 'available';
                            $statusColors = [
                                'available' => 'bg-green-100 text-green-800',
                                'occupied' => 'bg-red-100 text-red-800',
                                'maintenance' => 'bg-yellow-100 text-yellow-800',
                                'blocked' => 'bg-gray-100 text-gray-800',
                            ];
                        @endphp
                        <td class="px-2 py-3 text-center">
                            <a href="{{ $inventory ? route('properties.room-inventory.edit', [$property, $inventory]) : '#' }}" 
                               class="inline-block px-2 py-1 rounded text-xs font-semibold {{ $statusColors[$status] ?? 'bg-gray-100' }} hover:opacity-75">
                                {{ ucfirst($status) }}
                            </a>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Legend -->
<div class="mt-6 flex gap-6">
    <div class="flex items-center gap-2">
        <span class="px-3 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">Available</span>
    </div>
    <div class="flex items-center gap-2">
        <span class="px-3 py-1 rounded text-xs font-semibold bg-red-100 text-red-800">Occupied</span>
    </div>
    <div class="flex items-center gap-2">
        <span class="px-3 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-800">Maintenance</span>
    </div>
    <div class="flex items-center gap-2">
        <span class="px-3 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-800">Blocked</span>
    </div>
</div>

</x-app-layout>

