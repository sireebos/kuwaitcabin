<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Custom Field') }}
        </h2>
    </x-slot>

<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('properties.custom-fields.store', [$property, $entityType]) }}">
        @csrf

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Field Label *</label>
            <input type="text" name="label" value="{{ old('label') }}" placeholder="e.g., Passport Number, Special Dietary Needs" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('label') border-red-500 @enderror">
            @error('label')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Field Name (Internal) *</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g., passport_number, dietary_needs" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
            <p class="text-gray-500 text-xs mt-1">Use lowercase letters and underscores only</p>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Field Type *</label>
            <select name="type" id="field-type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('type') border-red-500 @enderror">
                <option value="">-- Select Type --</option>
                <option value="text" @selected(old('type') === 'text')>Text</option>
                <option value="number" @selected(old('type') === 'number')>Number</option>
                <option value="email" @selected(old('type') === 'email')>Email</option>
                <option value="phone" @selected(old('type') === 'phone')>Phone</option>
                <option value="date" @selected(old('type') === 'date')>Date</option>
                <option value="textarea" @selected(old('type') === 'textarea')>Textarea</option>
                <option value="select" @selected(old('type') === 'select')>Select Dropdown</option>
                <option value="checkbox" @selected(old('type') === 'checkbox')>Checkbox</option>
            </select>
            @error('type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div id="options-container" class="mb-6 hidden">
            <label class="block text-sm font-medium text-gray-700 mb-2">Options (for Select/Checkbox)</label>
            <textarea name="options" placeholder="Enter options separated by commas&#10;e.g., Option 1, Option 2, Option 3" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('options') }}</textarea>
            <p class="text-gray-500 text-xs mt-1">Separate each option with a comma</p>
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_required" value="1" @checked(old('is_required')) class="w-4 h-4 text-blue-600 rounded">
                <span class="ml-2 text-sm font-medium text-gray-700">Required Field</span>
            </label>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
            <input type="number" name="order" value="{{ old('order', 0) }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <p class="text-gray-500 text-xs mt-1">Lower numbers appear first</p>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Create Field
            </button>
            <a href="{{ route('properties.custom-fields.index', [$property, $entityType]) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
document.getElementById('field-type').addEventListener('change', function() {
    const container = document.getElementById('options-container');
    if (this.value === 'select' || this.value === 'checkbox') {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
    }
});

// Show on page load if needed
document.addEventListener('DOMContentLoaded', function() {
    const fieldType = document.getElementById('field-type');
    if (fieldType.value === 'select' || fieldType.value === 'checkbox') {
        document.getElementById('options-container').classList.remove('hidden');
    }
});
</script>

</x-app-layout>

