<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Menu Categories') }}
        </h2>
    </x-slot>

<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">{{ $menu->name }} - Categories</h1>
            <p class="text-gray-600 mt-1">Manage categories for this menu</p>
        </div>
        <a href="{{ route('properties.menus.categories.create', [$property, $menu]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Add Category
        </a>
    </div>

    @if ($categories->count())
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Description</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Items</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold">{{ $category->name }}</td>
                            <td class="px-6 py-4 text-gray-600 text-sm">{{ Str::limit($category->description, 50) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                    {{ $category->items()->count() }} items
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded text-sm font-semibold {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                <a href="{{ route('properties.menus.categories.items.index', [$property, $menu, $category]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">
                                    Items
                                </a>
                                <a href="{{ route('properties.menus.categories.edit', [$property, $menu, $category]) }}" class="text-gray-600 hover:text-gray-800 text-sm font-semibold">
                                    Edit
                                </a>
                                <form action="{{ route('properties.menus.categories.destroy', [$property, $menu, $category]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-500 mb-4">No categories created yet</p>
            <a href="{{ route('properties.menus.categories.create', [$property, $menu]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create First Category
            </a>
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('properties.menus.show', [$property, $menu]) }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Menu
        </a>
    </div>
</div>
</x-app-layout>

