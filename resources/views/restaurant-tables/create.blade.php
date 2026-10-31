<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Restaurant Table') }}
        </h2>
    </x-slot>

<div class="max-w-4xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Single Table Creation -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-6">Create Single Table</h2>

            <form action="{{ route('properties.restaurant-tables.store', $property) }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="table_number" class="block text-sm font-semibold text-gray-700 mb-2">Table Number *</label>
                    <input type="text" id="table_number" name="table_number" value="{{ old('table_number') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., T1, A1, Window-1">
                    @error('table_number')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="capacity" class="block text-sm font-semibold text-gray-700 mb-2">Capacity (Seats) *</label>
                    <input type="number" id="capacity" name="capacity" value="{{ old('capacity', 2) }}" min="1" max="20" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('capacity')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="section" class="block text-sm font-semibold text-gray-700 mb-2">Section</label>
                    <input type="text" id="section" name="section" value="{{ old('section') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Main Hall, Patio, VIP">
                    @error('section')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Near window, corner table">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-semibold">
                    Create Table
                </button>
            </form>
        </div>

        <!-- Bulk Table Creation -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-6">Create Multiple Tables</h2>

            <form action="{{ route('properties.restaurant-tables.bulk-create', $property) }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="start_number" class="block text-sm font-semibold text-gray-700 mb-2">Start Number *</label>
                    <input type="number" id="start_number" name="start_number" value="{{ old('start_number', 1) }}" min="1" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="1">
                    @error('start_number')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="count" class="block text-sm font-semibold text-gray-700 mb-2">Number of Tables *</label>
                    <input type="number" id="count" name="count" value="{{ old('count', 5) }}" min="1" max="100" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="5">
                    @error('count')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="capacity" class="block text-sm font-semibold text-gray-700 mb-2">Capacity (Seats) *</label>
                    <input type="number" id="capacity" name="capacity" value="{{ old('capacity', 2) }}" min="1" max="20" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('capacity')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="section" class="block text-sm font-semibold text-gray-700 mb-2">Section</label>
                    <input type="text" id="section" name="section" value="{{ old('section') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., Main Hall">
                    @error('section')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-semibold">
                    Create Multiple Tables
                </button>
            </form>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('properties.restaurant-tables.index', $property) }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Tables
        </a>
    </div>
</div>
</x-app-layout>

