<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menu Items') }}
        </h2>
    </x-slot>

<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">{{ $category->name }} - Items</h1>
            <p class="text-gray-600 mt-1">{{ $menu->name }}</p>
        </div>
        <a href="{{ route('properties.menus.categories.items.create', [$property, $menu, $category]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Add Item
        </a>
    </div>

    @if ($items->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($items as $item)
                <div class="bg-white rounded-lg shadow p-4 hover:shadow-lg transition">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="font-bold text-lg">{{ $item->name }}</h3>
                        <span class="text-xl font-bold text-blue-600">₦{{ number_format($item->price, 2) }}</span>
                    </div>

                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($item->description, 80) }}</p>

                    @if ($item->ingredients)
                        <div class="mb-3">
                            <p class="text-xs font-semibold text-gray-700 mb-1">Ingredients:</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach ($item->ingredients as $ingredient)
                                    <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-700">{{ $ingredient }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-3">
                        <span class="px-3 py-1 rounded text-sm font-semibold {{ $item->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $item->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('properties.menus.categories.items.edit', [$property, $menu, $category, $item]) }}" class="flex-1 bg-gray-600 text-white px-3 py-2 rounded hover:bg-gray-700 text-center text-sm font-semibold">
                            Edit
                        </a>
                        <form action="{{ route('properties.menus.categories.items.destroy', [$property, $menu, $category, $item]) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete this item?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700 text-sm font-semibold">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $items->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-500 mb-4">No items in this category</p>
            <a href="{{ route('properties.menus.categories.items.create', [$property, $menu, $category]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create First Item
            </a>
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('properties.menus.categories.index', [$property, $menu]) }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Categories
        </a>
    </div>
</div>
</x-app-layout>

