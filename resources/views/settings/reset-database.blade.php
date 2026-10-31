<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reset Database') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                        <h3 class="text-lg font-bold text-red-900 mb-2">⚠️ Warning: Dangerous Operation</h3>
                        <p class="text-red-800">This action will permanently delete ALL data from the database including:</p>
                        <ul class="list-disc list-inside text-red-800 mt-2 ml-2">
                            <li>All bookings and reservations</li>
                            <li>All orders (restaurant and bar)</li>
                            <li>All guests and customers</li>
                            <li>All rooms and inventory</li>
                            <li>All payments and folios</li>
                        </ul>
                        <p class="text-red-900 font-bold mt-3">This action CANNOT be undone!</p>
                    </div>

                    <form method="POST" action="{{ route('settings.confirmReset', $property) }}" class="space-y-6">
                        @csrf

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Enter Your Password to Confirm
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                                placeholder="Enter your password"
                                required
                            >
                            @error('password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmation Checkbox -->
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded">
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    name="confirmation" 
                                    value="yes" 
                                    class="w-4 h-4 text-red-600 rounded focus:ring-red-500"
                                    required
                                >
                                <span class="ml-3 text-sm font-semibold text-yellow-900">
                                    I understand that this will permanently delete all data and cannot be undone
                                </span>
                            </label>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4">
                            <button 
                                type="submit" 
                                class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition"
                            >
                                🗑️ Reset Database
                            </button>
                            <a 
                                href="{{ route('settings.edit', $property) }}" 
                                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-4 rounded-lg text-center transition"
                            >
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

