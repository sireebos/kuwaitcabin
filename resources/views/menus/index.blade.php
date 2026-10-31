<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menu Management') }}
        </h2>
    </x-slot>
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Menus</h1>
    <a href="{{ route('properties.menus.create', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Create Menu
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($menus as $menu)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-4">
                <h3 class="text-lg font-bold">{{ $menu->name }}</h3>
                <p class="text-gray-600 text-sm">{{ $menu->description }}</p>
            </div>

            <div class="space-y-2 mb-4 text-sm">
                <div>
                    <p class="text-gray-600">Type</p>
                    <p class="font-semibold">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($menu->type === 'food') bg-blue-100 text-blue-800
                            @elseif($menu->type === 'beverage') bg-green-100 text-green-800
                            @else bg-purple-100 text-purple-800
                            @endif">
                            {{ ucfirst($menu->type) }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-gray-600">Categories</p>
                    <p class="font-semibold">{{ $menu->categories->count() }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Items</p>
                    <p class="font-semibold">{{ $menu->categories->sum(fn($cat) => $cat->items->count()) }}</p>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('properties.menus.show', [$property, $menu]) }}" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center text-sm">
                    View
                </a>
                <a href="{{ route('properties.menus.edit', [$property, $menu]) }}" class="flex-1 bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 text-center text-sm">
                    Edit
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-500 mb-4">No menus created yet</p>
            <a href="{{ route('properties.menus.create', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create First Menu
            </a>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $menus->links() }}
</div>
</x-app-layout>

