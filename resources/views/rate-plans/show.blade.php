<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rate Plan Details') }}
        </h2>
    </x-slot>

<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">{{ $ratePlan->name }}</h1>
            <p class="text-gray-600">{{ $ratePlan->description }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('properties.rate-plans.edit', [$property, $ratePlan]) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Edit
            </a>
            <a href="{{ route('properties.rate-plans.index', $property) }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                Back
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="mb-6">
        <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $ratePlan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $ratePlan->is_active ? 'Active' : 'Inactive' }}
        </span>
    </div>

    <!-- Pricing Statistics -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-600 text-sm font-medium">Base Rate</div>
            <div class="text-3xl font-bold text-blue-600">{{ $property->currency }} {{ number_format($ratePlan->base_rate, 2) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-600 text-sm font-medium">Discount</div>
            <div class="text-3xl font-bold text-orange-600">{{ $ratePlan->discount_percentage }}%</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-600 text-sm font-medium">Effective Rate</div>
            <div class="text-3xl font-bold text-green-600">{{ $property->currency }} {{ number_format($ratePlan->getEffectiveRate(), 2) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-600 text-sm font-medium">Savings</div>
            <div class="text-3xl font-bold text-purple-600">{{ $property->currency }} {{ number_format($ratePlan->base_rate - $ratePlan->getEffectiveRate(), 2) }}</div>
        </div>
    </div>

    <!-- Rate Plan Information -->
    <div class="grid grid-cols-2 gap-6 mb-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-600 text-sm">Rate Plan Name</span>
                    <p class="font-medium">{{ $ratePlan->name }}</p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Property</span>
                    <p class="font-medium">
                        <a href="{{ route('properties.show', $property) }}" class="text-blue-600 hover:underline">{{ $property->name }}</a>
                    </p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Room Type</span>
                    <p class="font-medium">
                        <a href="{{ route('properties.room-types.show', [$property, $ratePlan->roomType]) }}" class="text-blue-600 hover:underline">{{ $ratePlan->roomType->name }}</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Pricing Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Pricing Information</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-gray-600 text-sm">Base Rate</span>
                    <p class="font-medium">{{ $property->currency }} {{ number_format($ratePlan->base_rate, 2) }}</p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Discount Percentage</span>
                    <p class="font-medium">{{ $ratePlan->discount_percentage }}%</p>
                </div>
                <div>
                    <span class="text-gray-600 text-sm">Effective Rate</span>
                    <p class="font-medium text-green-600">{{ $property->currency }} {{ number_format($ratePlan->getEffectiveRate(), 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Validity Period -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Validity Period</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <span class="text-gray-600 text-sm">Valid From</span>
                <p class="font-medium">{{ $ratePlan->valid_from->format('M d, Y') }}</p>
            </div>
            <div>
                <span class="text-gray-600 text-sm">Valid To</span>
                <p class="font-medium">{{ $ratePlan->valid_to->format('M d, Y') }}</p>
            </div>
            <div>
                <span class="text-gray-600 text-sm">Duration</span>
                <p class="font-medium">{{ $ratePlan->valid_from->diffInDays($ratePlan->valid_to) + 1 }} days</p>
            </div>
            <div>
                <span class="text-gray-600 text-sm">Status</span>
                <p class="font-medium">
                    @if(now() < $ratePlan->valid_from)
                        <span class="text-blue-600">Upcoming</span>
                    @elseif(now() > $ratePlan->valid_to)
                        <span class="text-gray-600">Expired</span>
                    @else
                        <span class="text-green-600">Active</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Timestamps -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Timestamps</h3>
        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
            <div>
                <span>Created:</span>
                <p>{{ $ratePlan->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div>
                <span>Last Updated:</span>
                <p>{{ $ratePlan->updated_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>

</x-app-layout>

