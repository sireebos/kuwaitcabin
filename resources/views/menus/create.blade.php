<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Menu') }}
        </h2>
    </x-slot>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Create New Menu</h2>

        <form method="POST" action="{{ route('properties.menus.store', $property) }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Menu Name *</label>
                <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Type *</label>
                <select id="type" name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Select Type</option>
                    <option value="food">Food</option>
                    <option value="beverage">Beverage</option>
                    <option value="both">Both</option>
                </select>
                @error('type') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="is_active" class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked class="rounded">
                    <span class="ml-2 text-sm font-semibold text-gray-700">Active</span>
                </label>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Create Menu
                </button>
                <a href="{{ route('properties.menus.index', $property) }}" class="flex-1 bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>

