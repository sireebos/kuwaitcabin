<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Branding & Assets') }}
        </h2>
    </x-slot>
  
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Preview Section -->
            <div class="mb-8 p-6 bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg">
                <h2 class="text-xl font-bold text-blue-900 mb-6">🎨 Current Branding Preview</h2>
                
                <div class="grid grid-cols-4 md:grid-cols-4 gap-6">
                    <!-- Logo Preview -->
                    <div class="bg-white rounded-lg p-4 shadow-md border border-blue-100">
                        <p class="text-sm font-semibold text-gray-700 mb-3">📍 Navigation Logo</p>
                        <div class="flex items-center justify-center h-24 bg-gray-50 rounded border border-gray-200" id="logoContainer">
                            @if($property->logo_path)
                                <img src="{{ asset($property->logo_path) }}" alt="Hotel Logo" class="h-20 w-auto object-contain" id="logoImg">
                            @else
                                <div class="text-center">
                                    <p class="text-gray-400 text-sm">No logo uploaded</p>
                                    <p class="text-gray-300 text-xs mt-1">🏨</p>
                                </div>
                            @endif 
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Displayed in navigation bar</p>
                    </div>

                    <!-- Light Logo Preview -->
                    <div class="bg-white rounded-lg p-4 shadow-md border border-blue-100">
                        <p class="text-sm font-semibold text-gray-700 mb-3">💡 Welcome Page Logo</p>
                        <div class="flex items-center justify-center h-24 bg-gray-900 rounded border border-gray-200" id="lightLogoContainer">
                            @if($property->light_logo_path)
                                <img src="{{ asset($property->light_logo_path) }}" alt="Light Logo" class="h-20 w-auto object-contain" id="lightLogoImg">
                            @else
                                <div class="text-center">
                                    <p class="text-gray-400 text-sm">No logo uploaded</p>
                                    <p class="text-gray-300 text-xs mt-1">✨</p>
                                </div>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Welcome page only (light background)</p>
                    </div>

                    <!-- Favicon Preview -->
                    <div class="bg-white rounded-lg p-4 shadow-md border border-blue-100">
                        <p class="text-sm font-semibold text-gray-700 mb-3">🔖 Browser Favicon</p>
                        <div class="flex items-center justify-center h-24 bg-gray-50 rounded border border-gray-200" id="faviconContainer">
                            @if($property->favicon_path)
                                <div class="flex flex-col items-center">
                                    <img src="{{ asset($property->favicon_path) }}" alt="Hotel Favicon" class="h-8 w-8 object-contain mb-2" id="faviconImg">
                                    <p class="text-xs text-gray-500">Browser tab</p>
                                </div>
                            @else
                                <div class="text-center">
                                    <p class="text-gray-400 text-sm">No favicon uploaded</p>
                                    <p class="text-gray-300 text-xs mt-1">📄</p>
                                </div>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Appears in browser tabs</p>
                    </div>

                    <!-- Background Preview -->
                    <div class="bg-white rounded-lg p-4 shadow-md border border-blue-100">
                        <p class="text-sm font-semibold text-gray-700 mb-3">🖼️ Welcome Background</p>
                        <div class="flex items-center justify-center h-24 bg-gray-50 rounded border border-gray-200 overflow-hidden" id="backgroundContainer">
                            @if($property->background_path)
                                <img src="{{ asset($property->background_path) }}" alt="Welcome Background" class="h-24 w-full object-cover" id="backgroundImg">
                            @else
                                <div class="text-center">
                                    <p class="text-gray-400 text-sm">No background uploaded</p>
                                    <p class="text-gray-300 text-xs mt-1">🌅</p>
                                </div>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Login/welcome page background</p>
                    </div>
                </div>
            </div>

            <!-- Upload Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-6">📤 Upload Branding Assets</h2>

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

                    <div class="grid grid-cols-4 md:grid-cols-4 gap-6">
                        <!-- Logo Upload -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">🖼️ Hotel Logo</label>
                            <p class="text-xs text-gray-500 mb-3">PNG, JPG, GIF (max 2MB)</p>

                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition cursor-pointer">
                                <input type="file" name="logo" id="logo" accept="image/*" class="hidden" onchange="previewLogo(event)">
                                <label for="logo" class="cursor-pointer block">
                                    <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20m-8-12l-3.172-3.172a4 4 0 00-5.656 0L28 20M20 32h8m-4-8a4 4 0 100-8 4 4 0 000 8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="text-xs text-blue-600 font-medium">Click to upload</p>
                                </label>
                            </div>

                            <div id="logoPreview" class="mt-3 hidden">
                                <img id="previewImage" src="" alt="Logo Preview" class="h-12 w-auto mx-auto border border-gray-300 rounded p-1">
                            </div>

                            @error('logo')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Light Logo Upload -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">💡 Welcome Logo</label>
                            <p class="text-xs text-gray-500 mb-3">PNG, JPG, GIF (max 2MB)</p>

                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition cursor-pointer">
                                <input type="file" name="light_logo" id="light_logo" accept="image/*" class="hidden" onchange="previewLightLogo(event)">
                                <label for="light_logo" class="cursor-pointer block">
                                    <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20m-8-12l-3.172-3.172a4 4 0 00-5.656 0L28 20M20 32h8m-4-8a4 4 0 100-8 4 4 0 000 8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="text-xs text-blue-600 font-medium">Click to upload</p>
                                </label>
                            </div>

                            <div id="lightLogoPreview" class="mt-3 hidden">
                                <img id="lightLogoPreviewImage" src="" alt="Light Logo Preview" class="h-12 w-auto mx-auto border border-gray-300 rounded p-1">
                            </div>

                            @error('light_logo')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Favicon Upload -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">🔖 Favicon</label>
                            <p class="text-xs text-gray-500 mb-3">PNG, JPG, GIF, ICO (max 1MB)</p>
                            
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition cursor-pointer">
                                <input type="file" name="favicon" id="favicon" accept="image/*" class="hidden" onchange="previewFavicon(event)">
                                <label for="favicon" class="cursor-pointer block">
                                    <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20m-8-12l-3.172-3.172a4 4 0 00-5.656 0L28 20M20 32h8m-4-8a4 4 0 100-8 4 4 0 000 8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="text-xs text-blue-600 font-medium">Click to upload</p>
                                </label>
                            </div>

                            <div id="faviconPreview" class="mt-3 hidden">
                                <img id="faviconPreviewImage" src="" alt="Favicon Preview" class="h-6 w-6 mx-auto border border-gray-300 rounded p-1">
                            </div>

                            @error('favicon')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Background Upload -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">🌅 Background</label>
                            <p class="text-xs text-gray-500 mb-3">PNG, JPG, GIF (max 5MB)</p>
                            
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition cursor-pointer">
                                <input type="file" name="background" id="background" accept="image/*" class="hidden" onchange="previewBackground(event)">
                                <label for="background" class="cursor-pointer block">
                                    <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20m-8-12l-3.172-3.172a4 4 0 00-5.656 0L28 20M20 32h8m-4-8a4 4 0 100-8 4 4 0 000 8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="text-xs text-blue-600 font-medium">Click to upload</p>
                                </label>
                            </div>

                            <div id="backgroundPreview" class="mt-3 hidden">
                                <img id="backgroundPreviewImage" src="" alt="Background Preview" class="h-12 w-auto mx-auto border border-gray-300 rounded p-1 object-cover">
                            </div>

                            @error('background')
                                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Upload Button -->
                    <div class="mt-6 flex gap-4">
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 font-medium">
                            ✓ Upload Assets
                        </button>
                        <a href="{{ route('settings.edit', $property) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 font-medium">
                            Back to Settings
                        </a>
                    </div>
                </form>
            </div>
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

        function previewLightLogo(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('lightLogoPreviewImage').src = e.target.result;
                    document.getElementById('lightLogoPreview').classList.remove('hidden');
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

        // Handle image load errors
        document.addEventListener('DOMContentLoaded', function() {
            const logoImg = document.getElementById('logoImg');
            const lightLogoImg = document.getElementById('lightLogoImg');
            const faviconImg = document.getElementById('faviconImg');
            const backgroundImg = document.getElementById('backgroundImg');

            if (logoImg) {
                logoImg.addEventListener('error', function() {
                    document.getElementById('logoContainer').innerHTML = '<div class="text-center"><p class="text-gray-400 text-sm">Image not found</p><p class="text-gray-300 text-xs mt-1">🏨</p></div>';
                });
            }

            if (lightLogoImg) {
                lightLogoImg.addEventListener('error', function() {
                    document.getElementById('lightLogoContainer').innerHTML = '<div class="text-center"><p class="text-gray-400 text-sm">Image not found</p><p class="text-gray-300 text-xs mt-1">✨</p></div>';
                });
            }

            if (faviconImg) {
                faviconImg.addEventListener('error', function() {
                    document.getElementById('faviconContainer').innerHTML = '<div class="text-center"><p class="text-gray-400 text-sm">Image not found</p><p class="text-gray-300 text-xs mt-1">📄</p></div>';
                });
            }

            if (backgroundImg) {
                backgroundImg.addEventListener('error', function() {
                    document.getElementById('backgroundContainer').innerHTML = '<div class="text-center"><p class="text-gray-400 text-sm">Image not found</p><p class="text-gray-300 text-xs mt-1">🌅</p></div>';
                });
            }
        });
    </script>
</x-app-layout>

