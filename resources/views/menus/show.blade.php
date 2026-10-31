<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menu Details') }}
        </h2>
    </x-slot>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-3xl font-bold">{{ $menu->name }}</h2>
                <p class="text-gray-600 mt-2">{{ $menu->description }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('properties.menus.categories.index', [$property, $menu]) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Manage Categories
                </a>
                <a href="{{ route('properties.menus.edit', [$property, $menu]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Edit Menu
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Type</h3>
                <p>{{ ucfirst($menu->type) }}</p>
            </div>
            <div>
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Status</h3>
                <span class="px-3 py-1 rounded text-sm font-semibold {{ $menu->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $menu->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

        <hr class="my-6">

        <h3 class="text-lg font-bold mb-4">Categories & Items</h3>
        
        @forelse ($menu->categories as $category)
            <div class="mb-6 bg-gray-50 rounded-lg p-4">
                <h4 class="text-lg font-semibold mb-3">{{ $category->name }}</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse ($category->items as $item)
                        <div class="bg-white rounded border border-gray-200 p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h5 class="font-semibold">{{ $item->name }}</h5>
                                <span class="text-lg font-bold text-blue-600">${{ number_format($item->price, 2) }}</span>
                            </div>
                            <p class="text-gray-600 text-sm">{{ $item->description }}</p>
                            @if($item->is_available)
                                <span class="inline-block mt-2 px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">Available</span>
                            @else
                                <span class="inline-block mt-2 px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-800">Unavailable</span>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 col-span-full">No items in this category</p>
                    @endforelse
                </div>
            </div>
        @empty
            <p class="text-gray-500">No categories in this menu</p>
        @endforelse

        <hr class="my-6">

        <div class="flex gap-2">
            <a href="{{ route('properties.menus.index', $property) }}" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 text-center">
                Back to Menus
            </a>
        </div>
    </div>
</div>
</x-app-layout>

