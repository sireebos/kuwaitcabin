<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Custom Fields - ' . ucfirst($entityType)) }}
        </h2>
    </x-slot>

<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Custom Fields for {{ ucfirst($entityType) }}</h1>
    <a href="{{ route('properties.custom-fields.create', [$property, $entityType]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Add Custom Field
    </a>
</div>

@if($customFields->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Label</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Field Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Required</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Order</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customFields as $field)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $field->label }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 font-mono">{{ $field->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">{{ $field->type }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if($field->is_required)
                                <span class="text-red-600 font-semibold">Yes</span>
                            @else
                                <span class="text-gray-500">No</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $field->order }}</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('properties.custom-fields.edit', [$property, $field]) }}" class="text-green-600 hover:text-green-800 mr-3">Edit</a>
                            <form method="POST" action="{{ route('properties.custom-fields.destroy', [$property, $field]) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-center">No custom fields defined yet</p>
    </div>
@endif

<div class="mt-6">
    <a href="{{ route('dashboard', $property) }}" class="text-blue-600 hover:text-blue-800">
        ← Back to Dashboard
    </a>
</div>

</x-app-layout>

