<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Management System - Installation Wizard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">🏨 Hotel Management System</h1>
                <p class="text-gray-600 text-lg">Installation Wizard</p>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700">Step 1 of 6</span>
                    <span class="text-sm font-semibold text-gray-700">Welcome</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 16.67%"></div>
                </div>
            </div>

            <!-- Content -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Welcome to the Installation Wizard</h2>
                <p class="text-gray-700 mb-4">
                    This wizard will guide you through the installation and configuration of the Hotel Management System on your live domain.
                </p>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                    <h3 class="font-semibold text-blue-900 mb-2">What you'll configure:</h3>
                    <ul class="list-disc list-inside text-blue-800 space-y-1">
                        <li>Database connection (MySQL or SQLite)</li>
                        <li>Environment variables</li>
                        <li>Run database migrations</li>
                        <li>Create admin user account</li>
                        <li>Configure image storage</li>
                    </ul>
                </div>
                <p class="text-gray-600 text-sm">
                    Make sure you have your database credentials ready before proceeding.
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('installation.database') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-center transition">
                    Next: Database Setup →
                </a>
            </div>
        </div>
    </div>
</body>
</html>

