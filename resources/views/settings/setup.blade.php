<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home Page Setup') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <strong>Error:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('settings.updateSetup', $property) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Home Page Title -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Home Page Title</label>
                <input type="text" name="home_title" value="{{ old('home_title', $property->home_title) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('home_title') border-red-500 @enderror"
                       placeholder="Welcome to Our Hotel">
                @error('home_title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Home Page Description -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Home Page Description</label>
                <textarea name="home_description" rows="4" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('home_description') border-red-500 @enderror"
                          placeholder="Describe your hotel and its features...">{{ old('home_description', $property->home_description) }}</textarea>
                @error('home_description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Home Page Image -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Home Page Image</label>
                @if ($property->home_image_path)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $property->home_image_path) }}" alt="Home Image" class="h-40 rounded-lg">
                    </div>
                @endif
                <input type="file" name="home_image" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('home_image') border-red-500 @enderror">
                <p class="text-gray-500 text-sm mt-1">Max size: 5MB (JPEG, PNG, GIF)</p>
                @error('home_image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Home Image Link -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Home Image Link (Optional)</label>
                <input type="url" name="home_image_link" value="{{ old('home_image_link', $property->home_image_link) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('home_image_link') border-red-500 @enderror"
                       placeholder="https://example.com">
                <p class="text-gray-500 text-sm mt-1">URL to open when image is clicked</p>
                @error('home_image_link')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Features Enabled -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Enabled Features</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="features_enabled[]" value="bookings" 
                               @if(in_array('bookings', json_decode($property->features_enabled ?? '[]', true))) checked @endif
                               class="rounded border-gray-300">
                        <span class="ml-2">Show Bookings</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="features_enabled[]" value="rooms" 
                               @if(in_array('rooms', json_decode($property->features_enabled ?? '[]', true))) checked @endif
                               class="rounded border-gray-300">
                        <span class="ml-2">Show Rooms</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="features_enabled[]" value="restaurant" 
                               @if(in_array('restaurant', json_decode($property->features_enabled ?? '[]', true))) checked @endif
                               class="rounded border-gray-300">
                        <span class="ml-2">Show Restaurant</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="features_enabled[]" value="bar" 
                               @if(in_array('bar', json_decode($property->features_enabled ?? '[]', true))) checked @endif
                               class="rounded border-gray-300">
                        <span class="ml-2">Show Bar</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Save Configuration
                </button>
                <a href="{{ route('properties.show', $property) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>

