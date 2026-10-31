<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-red-50 to-orange-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-auto px-6">
        <div class="bg-white rounded-lg shadow-xl p-8 text-center">
            <!-- 500 Icon -->
            <div class="mb-6">
                <div class="text-6xl font-bold text-red-600 mb-2">500</div>
                <div class="text-2xl font-semibold text-gray-800">Server Error</div>
            </div>

            <!-- Error Message -->
            <p class="text-gray-600 mb-8">
                Oops! Something went wrong on our end. Our team has been notified and is working to fix it.
            </p>

            <!-- Action Buttons -->
            <div class="space-y-3">
                @auth
                    <a href="{{ route('dashboard', ['property' => auth()->user()->property_id ?? 1]) }}" 
                       class="block w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                        Go to Dashboard
                    </a>
                    <a href="{{ route('properties.index') }}" 
                       class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-4 rounded-lg transition duration-200">
                        View All Properties
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="block w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                        Go to Login
                    </a>
                    <a href="{{ route('welcome') }}" 
                       class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-4 rounded-lg transition duration-200">
                        Go to Home
                    </a>
                @endauth
            </div>

            <!-- Footer -->
            <p class="text-gray-500 text-xs mt-8">
                Error ID: {{ $exception->getCode() ?? 'N/A' }}
            </p>
        </div>
    </div>
</body>
</html>

