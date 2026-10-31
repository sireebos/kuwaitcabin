<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Menu Item') }}
        </h2>
    </x-slot>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Menu Item</h1>

        <form action="{{ route('properties.menus.categories.items.update', [$property, $menu, $category, $item]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Item Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $item->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $item->description) }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Price (₦) *</label>
                <input type="number" id="price" name="price" value="{{ old('price', $item->price) }}" step="0.01" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('price')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="ingredients" class="block text-sm font-semibold text-gray-700 mb-2">Ingredients</label>
                <textarea id="ingredients" name="ingredients" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('ingredients', $item->ingredients ? implode(', ', $item->ingredients) : '') }}</textarea>
                <p class="text-gray-500 text-sm mt-1">Separate each ingredient with a comma</p>
                @error('ingredients')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="modifiers" class="block text-sm font-semibold text-gray-700 mb-2">Modifiers (Add-ons)</label>
                <textarea id="modifiers" name="modifiers" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('modifiers', $item->modifiers ? implode(', ', $item->modifiers) : '') }}</textarea>
                <p class="text-gray-500 text-sm mt-1">Separate each modifier with a comma</p>
                @error('modifiers')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="sort_order" class="block text-sm font-semibold text-gray-700 mb-2">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $item->sort_order) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-gray-500 text-sm mt-1">Lower numbers appear first</p>
                @error('sort_order')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', $item->is_available) ? 'checked' : '' }} class="rounded border-gray-300">
                    <span class="ml-2 text-sm font-semibold text-gray-700">Available</span>
                </label>
                @error('is_available')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-semibold">
                    Update Item
                </button>
                <a href="{{ route('properties.menus.categories.items.index', [$property, $menu, $category]) }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 text-center font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>

