<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Room') }}
        </h2>
    </x-slot>

<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('properties.rooms.store', $property) }}">
        @csrf

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Starting Room Number *</label>
                <input type="number" name="room_number" value="{{ old('room_number') }}" placeholder="e.g., 101" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('room_number') border-red-500 @enderror">
                @error('room_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1">The starting room number (e.g., 101)</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" max="100" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('quantity') border-red-500 @enderror">
                @error('quantity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1">Number of rooms to create (default: 1)</p>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Room Type *</label>
            <select name="room_type_id" id="room_type_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('room_type_id') border-red-500 @enderror" onchange="updateRoomTypePrice()">
                <option value="">-- Select Room Type --</option>
                @foreach ($roomTypes as $type)
                    <option value="{{ $type->id }}" data-price="{{ $type->base_price }}" @selected(old('room_type_id') == $type->id)>
                        {{ $type->name }} (₦{{ number_format($type->base_price, 2) }})
                    </option>
                @endforeach
            </select>
            @error('room_type_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Room Price Section -->
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">Room Price</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Room Type Base Price</label>
                    <input type="text" id="room_type_price" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600" placeholder="Select a room type">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Custom Room Price (Optional)</label>
                    <div class="flex items-center gap-2">
                        <span class="text-lg font-semibold text-gray-700">₦</span>
                        <input type="number" name="price" id="room_price" value="{{ old('price') }}" min="0" step="0.01" placeholder="Leave empty to use room type price" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('price') border-red-500 @enderror">
                    </div>
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-xs mt-1">If set, this price will override the room type base price</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Floor *</label>
                <input type="number" name="floor" value="{{ old('floor', 1) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('floor') border-red-500 @enderror">
                @error('floor')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Wing (Optional)</label>
                <input type="text" name="wing" value="{{ old('wing') }}" placeholder="e.g., A, B, North, etc." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                <option value="available" @selected(old('status') === 'available')>Available</option>
                <option value="maintenance" @selected(old('status') === 'maintenance')>Maintenance</option>
                <option value="blocked" @selected(old('status') === 'blocked')>Blocked</option>
            </select>
            @error('status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
            <textarea name="notes" rows="4" placeholder="Add any notes about this room..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Create Room
            </button>
            <a href="{{ route('properties.rooms.index', $property) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
function updateRoomTypePrice() {
    const select = document.getElementById('room_type_id');
    const selectedOption = select.options[select.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    const roomTypePriceInput = document.getElementById('room_type_price');

    if (price) {
        roomTypePriceInput.value = '₦' + parseFloat(price).toLocaleString('en-NG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    } else {
        roomTypePriceInput.value = '';
    }
}

// Initialize on page load if room type is already selected
document.addEventListener('DOMContentLoaded', function() {
    updateRoomTypePrice();
});
</script>

</x-app-layout>

