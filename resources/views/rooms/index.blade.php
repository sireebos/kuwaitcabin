<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Room Management') }}
        </h2>
    </x-slot>

<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Rooms</h1>
    <div class="flex gap-2">
        <button id="deleteSelectedBtn" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 hidden" onclick="deleteSelected()">
            🗑️ Delete Selected
        </button>
        <a href="{{ route('properties.rooms.create', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Add Room
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                </th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Room Number</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Price</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Floor</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Wing</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rooms as $room)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <input type="checkbox" class="room-checkbox" value="{{ $room->id }}" onchange="toggleDeleteButton()">
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $room->room_number }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $room->roomType->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <span class="font-semibold text-blue-600">₦{{ number_format($room->getEffectivePrice(), 2) }}</span>
                        @if($room->price)
                            <span class="text-xs text-gray-500">(custom)</span>
                        @else
                            <span class="text-xs text-gray-500">(type)</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $room->floor }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $room->wing ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($room->status === 'available') bg-green-100 text-green-800
                            @elseif($room->status === 'occupied') bg-blue-100 text-blue-800
                            @elseif($room->status === 'maintenance') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($room->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('properties.rooms.show', [$property, $room]) }}" class="text-blue-600 hover:text-blue-800 mr-3">View</a>
                        <a href="{{ route('properties.rooms.edit', [$property, $room]) }}" class="text-green-600 hover:text-green-800 mr-3">Edit</a>
                        <form method="POST" action="{{ route('properties.rooms.destroy', [$property, $room]) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No rooms found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $rooms->links() }}
</div>

<!-- Hidden form for bulk delete -->
<form id="bulkDeleteForm" method="POST" action="{{ route('properties.rooms.bulk-delete', $property) }}" style="display:none;">
    @csrf
    @method('POST')
    <input type="hidden" id="roomIds" name="room_ids" value="">
</form>

<script>
function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.room-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    toggleDeleteButton();
}

function toggleDeleteButton() {
    const checkboxes = document.querySelectorAll('.room-checkbox:checked');
    const deleteBtn = document.getElementById('deleteSelectedBtn');
    if (checkboxes.length > 0) {
        deleteBtn.classList.remove('hidden');
    } else {
        deleteBtn.classList.add('hidden');
    }
}

function deleteSelected() {
    const checkboxes = document.querySelectorAll('.room-checkbox:checked');
    if (checkboxes.length === 0) {
        alert('Please select at least one room to delete');
        return;
    }

    if (!confirm(`Are you sure you want to delete ${checkboxes.length} room(s)? This action cannot be undone.`)) {
        return;
    }

    const roomIds = Array.from(checkboxes).map(cb => cb.value);
    document.getElementById('roomIds').value = JSON.stringify(roomIds);
    document.getElementById('bulkDeleteForm').submit();
}
</script>

</x-app-layout>

