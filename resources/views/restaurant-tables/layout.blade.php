<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Restaurant Layout') }}
        </h2>
    </x-slot>

<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-3xl font-bold">Restaurant Layout</h1>
        <a href="{{ route('properties.restaurant-tables.index', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            ← Back to Tables List
        </a>
    </div>

    @if ($tables->count())
        @foreach ($sections as $section)
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">{{ $section ?? 'No Section' }}</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach ($tables->where('section', $section) as $table)
                        <div class="relative group">
                            <div class="bg-white rounded-lg shadow-lg p-4 text-center cursor-pointer hover:shadow-xl transition transform hover:scale-105
                                @if($table->status === 'available') border-4 border-green-500
                                @elseif($table->status === 'occupied') border-4 border-red-500
                                @elseif($table->status === 'reserved') border-4 border-yellow-500
                                @else border-4 border-gray-500
                                @endif">
                                <div class="text-3xl font-bold text-gray-800">{{ $table->table_number }}</div>
                                <div class="text-sm text-gray-600 mt-1">{{ $table->capacity }} seats</div>
                                <div class="text-xs font-semibold mt-2
                                    @if($table->status === 'available') text-green-600
                                    @elseif($table->status === 'occupied') text-red-600
                                    @elseif($table->status === 'reserved') text-yellow-600
                                    @else text-gray-600
                                    @endif">
                                    {{ ucfirst($table->status) }}
                                </div>
                            </div>

                            <!-- Hover Menu -->
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 bg-gray-800 text-white rounded-lg shadow-lg p-2 opacity-0 group-hover:opacity-100 transition whitespace-nowrap z-10">
                                <a href="{{ route('properties.restaurant-tables.edit', [$property, $table]) }}" class="block px-3 py-1 hover:bg-gray-700 rounded text-sm">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Tables without section -->
        @if ($tables->where('section', null)->count())
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Unassigned</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach ($tables->where('section', null) as $table)
                        <div class="relative group">
                            <div class="bg-white rounded-lg shadow-lg p-4 text-center cursor-pointer hover:shadow-xl transition transform hover:scale-105
                                @if($table->status === 'available') border-4 border-green-500
                                @elseif($table->status === 'occupied') border-4 border-red-500
                                @elseif($table->status === 'reserved') border-4 border-yellow-500
                                @else border-4 border-gray-500
                                @endif">
                                <div class="text-3xl font-bold text-gray-800">{{ $table->table_number }}</div>
                                <div class="text-sm text-gray-600 mt-1">{{ $table->capacity }} seats</div>
                                <div class="text-xs font-semibold mt-2
                                    @if($table->status === 'available') text-green-600
                                    @elseif($table->status === 'occupied') text-red-600
                                    @elseif($table->status === 'reserved') text-yellow-600
                                    @else text-gray-600
                                    @endif">
                                    {{ ucfirst($table->status) }}
                                </div>
                            </div>

                            <!-- Hover Menu -->
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 bg-gray-800 text-white rounded-lg shadow-lg p-2 opacity-0 group-hover:opacity-100 transition whitespace-nowrap z-10">
                                <a href="{{ route('properties.restaurant-tables.edit', [$property, $table]) }}" class="block px-3 py-1 hover:bg-gray-700 rounded text-sm">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Legend -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold mb-4">Legend</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 border-4 border-green-500 rounded"></div>
                    <span>Available</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 border-4 border-red-500 rounded"></div>
                    <span>Occupied</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 border-4 border-yellow-500 rounded"></div>
                    <span>Reserved</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 border-4 border-gray-500 rounded"></div>
                    <span>Maintenance</span>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-6 text-center">
            <p class="text-gray-500 mb-4">No tables created yet</p>
            <a href="{{ route('properties.restaurant-tables.create', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create Tables
            </a>
        </div>
    @endif
</div>
</x-app-layout>

