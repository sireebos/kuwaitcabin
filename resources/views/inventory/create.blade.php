<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Inventory Item') }}
        </h2>
    </x-slot>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Add New Inventory Item</h2>

        <form method="POST" action="{{ route('properties.inventory.store', $property) }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Item Name *</label>
                <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="sku" class="block text-sm font-semibold text-gray-700 mb-2">SKU *</label>
                <input type="text" id="sku" name="sku" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('sku') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="type" class="text-sm font-semibold text-gray-700">Type *</label>
                        <a href="{{ route('properties.inventory-types.index', $property) }}" target="_blank" class="text-xs bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 font-medium">
                            Manage Types
                        </a>
                    </div>
                    <select id="type" name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Type</option>
                        @foreach($property->inventoryTypes()->where('is_active', true)->orderBy('name')->get() as $type)
                            <option value="{{ $type->name }}">{{ ucfirst($type->name) }}</option>
                        @endforeach
                    </select>
                    @error('type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="unit" class="block text-sm font-semibold text-gray-700 mb-2">Unit *</label>
                    <input type="text" id="unit" name="unit" placeholder="kg, L, pcs, etc." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('unit') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-2">Initial Quantity *</label>
                    <input type="number" id="quantity" name="quantity" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('quantity') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="reorder_level" class="block text-sm font-semibold text-gray-700 mb-2">Reorder Level *</label>
                    <input type="number" id="reorder_level" name="reorder_level" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('reorder_level') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="unit_cost" class="block text-sm font-semibold text-gray-700 mb-2">Unit Cost *</label>
                    <input type="number" id="unit_cost" name="unit_cost" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('unit_cost') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="supplier" class="block text-sm font-semibold text-gray-700 mb-2">Supplier</label>
                    <input type="text" id="supplier" name="supplier" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('supplier') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Create Item
                </button>
                <a href="{{ route('properties.inventory.index', $property) }}" class="flex-1 bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

</x-app-layout>

