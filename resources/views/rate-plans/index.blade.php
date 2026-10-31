<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rate Plans Management') }}
        </h2>
    </x-slot>

<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold">Rate Plans - {{ $property->name }}</h1>
    <a href="{{ route('properties.rate-plans.create', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Add Rate Plan
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Room Type</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Base Rate</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Discount</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Effective Rate</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Valid Period</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ratePlans as $ratePlan)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $ratePlan->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $ratePlan->roomType->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $property->currency }} {{ number_format($ratePlan->base_rate, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $ratePlan->discount_percentage }}%</td>
                    <td class="px-6 py-4 text-sm font-semibold text-green-600">{{ $property->currency }} {{ number_format($ratePlan->getEffectiveRate(), 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $ratePlan->valid_from->format('M d, Y') }} - {{ $ratePlan->valid_to->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $ratePlan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $ratePlan->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('properties.rate-plans.show', [$property, $ratePlan]) }}" class="text-blue-600 hover:text-blue-800 mr-3">View</a>
                        <a href="{{ route('properties.rate-plans.edit', [$property, $ratePlan]) }}" class="text-green-600 hover:text-green-800 mr-3">Edit</a>
                        <form method="POST" action="{{ route('properties.rate-plans.destroy', [$property, $ratePlan]) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No rate plans found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $ratePlans->links() }}
</div>

</x-app-layout>

