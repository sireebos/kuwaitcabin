<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Rate Plan') }}
        </h2>
    </x-slot>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Rate Plan: {{ $ratePlan->name }}</h1>

        <form method="POST" action="{{ route('properties.rate-plans.update', [$property, $ratePlan]) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Basic Information -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rate Plan Name *</label>
                        <input type="text" name="name" value="{{ old('name', $ratePlan->name) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Room Type *</label>
                        <select name="room_type_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('room_type_id') border-red-500 @enderror">
                            <option value="">Select a room type</option>
                            @foreach($roomTypes as $roomType)
                                <option value="{{ $roomType->id }}" {{ old('room_type_id', $ratePlan->room_type_id) == $roomType->id ? 'selected' : '' }}>
                                    {{ $roomType->name }} ({{ $property->currency }} {{ number_format($roomType->base_price, 2) }})
                                </option>
                            @endforeach
                        </select>
                        @error('room_type_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $ratePlan->description) }}</textarea>
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold mb-4">Pricing Information</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Base Rate ({{ $property->currency }}) *</label>
                        <input type="number" name="base_rate" value="{{ old('base_rate', $ratePlan->base_rate) }}" required min="0" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('base_rate') border-red-500 @enderror">
                        @error('base_rate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Discount Percentage (%)</label>
                        <input type="number" name="discount_percentage" value="{{ old('discount_percentage', $ratePlan->discount_percentage) }}" min="0" max="100" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('discount_percentage') border-red-500 @enderror">
                        @error('discount_percentage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-gray-700">
                        <strong>Effective Rate:</strong> <span id="effectiveRate">{{ $property->currency }} {{ number_format($ratePlan->getEffectiveRate(), 2) }}</span>
                    </p>
                </div>
            </div>

            <!-- Validity Period -->
            <div class="border-b pb-6">
                <h3 class="text-lg font-semibold mb-4">Validity Period</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Valid From *</label>
                        <input type="date" name="valid_from" value="{{ old('valid_from', $ratePlan->valid_from->format('Y-m-d')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('valid_from') border-red-500 @enderror">
                        @error('valid_from') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Valid To *</label>
                        <input type="date" name="valid_to" value="{{ old('valid_to', $ratePlan->valid_to->format('Y-m-d')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('valid_to') border-red-500 @enderror">
                        @error('valid_to') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="pb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $ratePlan->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Active</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Update Rate Plan
                </button>
                <a href="{{ route('properties.rate-plans.show', [$property, $ratePlan]) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const baseRateInput = document.querySelector('input[name="base_rate"]');
    const discountInput = document.querySelector('input[name="discount_percentage"]');
    const effectiveRateSpan = document.getElementById('effectiveRate');
    const currency = '{{ $property->currency }}';

    function updateEffectiveRate() {
        const baseRate = parseFloat(baseRateInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const effectiveRate = baseRate - (baseRate * discount / 100);
        effectiveRateSpan.textContent = currency + ' ' + effectiveRate.toFixed(2);
    }

    baseRateInput.addEventListener('input', updateEffectiveRate);
    discountInput.addEventListener('input', updateEffectiveRate);
    updateEffectiveRate();
</script>

</x-app-layout>

