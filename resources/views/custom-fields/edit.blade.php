<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Custom Field') }}
        </h2>
    </x-slot>

<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('properties.custom-fields.update', [$property, $customField]) }}">
        @csrf
        @method('PATCH')

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Field Label *</label>
            <input type="text" name="label" value="{{ old('label', $customField->label) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('label') border-red-500 @enderror">
            @error('label')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Field Name (Internal)</label>
            <input type="text" value="{{ $customField->name }}" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
            <p class="text-gray-500 text-xs mt-1">Field name cannot be changed</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Field Type *</label>
            <input type="text" value="{{ $customField->type }}" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
            <p class="text-gray-500 text-xs mt-1">Field type cannot be changed</p>
        </div>

        @if($customField->type === 'select' || $customField->type === 'checkbox')
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                <textarea name="options" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('options', implode(', ', $customField->options ?? [])) }}</textarea>
                <p class="text-gray-500 text-xs mt-1">Separate each option with a comma</p>
            </div>
        @endif

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_required" value="1" @checked(old('is_required', $customField->is_required)) class="w-4 h-4 text-blue-600 rounded">
                <span class="ml-2 text-sm font-medium text-gray-700">Required Field</span>
            </label>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
            <input type="number" name="order" value="{{ old('order', $customField->order) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <p class="text-gray-500 text-xs mt-1">Lower numbers appear first</p>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Update Field
            </button>
            <a href="{{ route('properties.custom-fields.index', [$property, $customField->entity_type]) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Cancel
            </a>
        </div>
    </form>
</div>

</x-app-layout>

