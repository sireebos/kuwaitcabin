<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hotel Settings') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Hotel Settings</h1>
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('settings.setup', $property) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.5 1.5H3.75A2.25 2.25 0 001.5 3.75v12.5A2.25 2.25 0 003.75 18.5h12.5a2.25 2.25 0 002.25-2.25V9.5M18.5 1.5v6m0 0h-6m6 0L12 1.5"/>
                    </svg>
                    Home Page Setup
                </a>
                <a href="{{ route('settings.branding', $property) }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                    </svg>
                    Branding & Assets
                </a>
                <a href="{{ route('settings.api-documentation', $property) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z"/>
                    </svg>
                    🔌 API Documentation
                </a>
                <a href="{{ route('settings.resetDatabase', $property) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                    </svg>
                    🗑️ Reset Database
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h3 class="text-red-800 font-semibold mb-2">Errors:</h3>
                <ul class="text-red-700 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800 font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('settings.update', $property) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- Hotel Name -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Hotel Name *</label>
                <input type="text" name="name" value="{{ old('name', $property->name) }}" placeholder="e.g., Grand Hotel & Restaurant" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                <p class="text-gray-500 text-xs mt-1">The official name of your hotel</p>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Hotel Display Name -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Hotel Display Name *</label>
                <input type="text" name="display_name" value="{{ old('display_name', $property->display_name ?? $property->name) }}" placeholder="e.g., Kuwait Cabin" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('display_name') border-red-500 @enderror">
                <p class="text-gray-500 text-xs mt-1">This name will be displayed throughout the application</p>
                @error('display_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                <input type="email" name="email" value="{{ old('email', $property->email) }}" placeholder="e.g., info@hotel.com" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                <p class="text-gray-500 text-xs mt-1">Hotel contact email address</p>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                <input type="text" name="phone" value="{{ old('phone', $property->phone) }}" placeholder="e.g., +1-212-555-0100" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                <p class="text-gray-500 text-xs mt-1">Hotel contact phone number</p>
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Address -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                <input type="text" name="address" value="{{ old('address', $property->address) }}" placeholder="e.g., 123 Main Street" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror">
                <p class="text-gray-500 text-xs mt-1">Street address of the hotel</p>
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- City -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                <input type="text" name="city" value="{{ old('city', $property->city) }}" placeholder="e.g., New York" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('city') border-red-500 @enderror">
                <p class="text-gray-500 text-xs mt-1">City where the hotel is located</p>
                @error('city')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Country -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                <input type="text" name="country" value="{{ old('country', $property->country) }}" placeholder="e.g., United States" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('country') border-red-500 @enderror">
                <p class="text-gray-500 text-xs mt-1">Country where the hotel is located</p>
                @error('country')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tax Rate -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tax Rate (%) *</label>
                <input type="number" name="tax_rate" value="{{ old('tax_rate', $property->tax_rate !== null ? $property->tax_rate : 10) }}" placeholder="e.g., 10" step="0.01" min="0" max="100" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('tax_rate') border-red-500 @enderror">
                <p class="text-gray-500 text-xs mt-1">Tax percentage applied to room charges (e.g., 10 for 10%)</p>
                @error('tax_rate')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>



            <!-- Hotel Information Summary -->
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">📋 Hotel Information Summary</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-blue-700 font-medium">Hotel Name:</p>
                        <p class="font-semibold text-gray-900">{{ $property->name }}</p>
                    </div>
                    <div>
                        <p class="text-blue-700 font-medium">Display Name:</p>
                        <p class="font-semibold text-gray-900">{{ $property->display_name ?? $property->name }}</p>
                    </div>
                    <div>
                        <p class="text-blue-700 font-medium">Email:</p>
                        <p class="font-semibold text-gray-900">{{ $property->email }}</p>
                    </div>
                    <div>
                        <p class="text-blue-700 font-medium">Phone:</p>
                        <p class="font-semibold text-gray-900">{{ $property->phone }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-blue-700 font-medium">Address:</p>
                        <p class="font-semibold text-gray-900">{{ $property->address }}, {{ $property->city }}, {{ $property->country }}</p>
                    </div>
                    <div>
                        <p class="text-blue-700 font-medium">Tax Rate:</p>
                        <p class="font-semibold text-gray-900">{{ $property->tax_rate !== null ? $property->tax_rate : 10 }}%</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium">
                    Save Settings
                </button>
                <a href="{{ route('dashboard', $property) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Admin Settings Section -->
    <div class="max-w-6xl mx-auto mt-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">⚙️ Admin Settings</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Manage Users -->
            <a href="{{ route('properties.users.index', $property) }}" class="group">
                <div class="bg-white rounded-lg shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden h-full">
                    <div class="bg-gradient-to-br from-red-400 to-red-600 p-6 flex items-center justify-center h-40 group-hover:from-red-500 group-hover:to-red-700">
                        <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                        </svg>
                    </div>
                    <div class="p-4 text-center">
                        <p class="font-bold text-lg text-gray-800">👤 Manage Users</p>
                        <p class="text-sm text-gray-600 mt-2">Add, edit, and manage user accounts and roles</p>
                    </div>
                </div>
            </a>

            <!-- Sales Report -->
            <a href="{{ route('reports.daily-sales', $property) }}" class="group">
                <div class="bg-white rounded-lg shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden h-full">
                    <div class="bg-gradient-to-br from-green-400 to-green-600 p-6 flex items-center justify-center h-40 group-hover:from-green-500 group-hover:to-green-700">
                        <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <div class="p-4 text-center">
                        <p class="font-bold text-lg text-gray-800">💵 Sales Report</p>
                        <p class="text-sm text-gray-600 mt-2">View daily sales and transactions by user</p>
                    </div>
                </div>
            </a>

            <!-- Bookings Report -->
            <a href="{{ route('reports.daily-bookings', $property) }}" class="group">
                <div class="bg-white rounded-lg shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden h-full">
                    <div class="bg-gradient-to-br from-indigo-400 to-indigo-600 p-6 flex items-center justify-center h-40 group-hover:from-indigo-500 group-hover:to-indigo-700">
                        <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v2H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v2H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="p-4 text-center">
                        <p class="font-bold text-lg text-gray-800">📋 Bookings Report</p>
                        <p class="text-sm text-gray-600 mt-2">View daily bookings and reservations by user</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Guest Fields and Inventory Fields Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <!-- Guest Fields -->
            <a href="{{ route('dashboard', $property) }}" class="group">
                <div class="bg-white rounded-lg shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden h-full">
                    <div class="bg-gradient-to-br from-blue-400 to-blue-600 p-6 flex items-center justify-center h-40 group-hover:from-blue-500 group-hover:to-blue-700">
                        <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v2h8v-2zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-2a4 4 0 00-8 0v2h8z"/>
                        </svg>
                    </div>
                    <div class="p-4 text-center">
                        <p class="font-bold text-lg text-gray-800">⚙️ Guest Fields</p>
                        <p class="text-sm text-gray-600 mt-2">Manage custom guest profile fields</p>
                    </div>
                </div>
            </a>

            <!-- Inventory Fields -->
            <a href="{{ route('dashboard', $property) }}" class="group">
                <div class="bg-white rounded-lg shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden h-full">
                    <div class="bg-gradient-to-br from-purple-400 to-purple-600 p-6 flex items-center justify-center h-40 group-hover:from-purple-500 group-hover:to-purple-700">
                        <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                        </svg>
                    </div>
                    <div class="p-4 text-center">
                        <p class="font-bold text-lg text-gray-800">⚙️ Inventory Fields</p>
                        <p class="text-sm text-gray-600 mt-2">Manage custom inventory item fields</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <script>
        function previewLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImage').src = e.target.result;
                    document.getElementById('logoPreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function previewFavicon(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('faviconPreviewImage').src = e.target.result;
                    document.getElementById('faviconPreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function previewBackground(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('backgroundPreviewImage').src = e.target.result;
                    document.getElementById('backgroundPreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>

